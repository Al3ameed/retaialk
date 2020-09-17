<?php

namespace App\Http\Controllers;

use App\Addresses;
use App\OnlinePaymentLogs;
use App\User;
use App\UserOrderPayment;
use App\Orders;
use App\Cart;
use Carbon\Carbon;
use Mail;
use Illuminate\Support\Facades\DB;

class WeAcceptPaymentsController extends ApiController
{

    public function checkout(\Illuminate\Http\Request $request)
    {
        $token = getTokenFromReq(request());
        $final_total_price = $request->final_total_price;

        $user = \App\User::where('token', '=', $token)->with('cart')->first();
        $sales_order_controller = new SalesOrderController();
        if (!isset($user->cart->CartItems) || count($user->cart->CartItems) < 0) {
            return $this->respondWithErorr("Error", "Cart Is Empty");
        }
        $cart_calculations = $sales_order_controller->cartCalculations($request)->original;
        $final_total_price = round((int)$cart_calculations['final_total_price']);
        $final_total_price = (int)round($final_total_price, 0);

        $user_id = $user->id;

        $profile = $this->Authentication();

        if (isset($profile->message)) {
            return $this->respondWithErorr("Error", $profile->message);
        }
        if (!isset($profile->token)) {
            return $this->respondWithErorr("Error", "Enable to get user profile!");
        }

        $address = Addresses::find($request->address_id);
        if (!$address) return $this->respondValidationErorr(400, 'Invalid address');


        // 2- Create Order

        if ($request->has('name') && $request->name != '') {
            $name = explode(' ', $request->name);
            $first_name = (isset($name[0])) ? $name[0] : 'NA';
            $last_name = (isset($name[1])) ? $name[1] : 'NA';
        } else {
            $name = explode(' ', $user->name);
            $first_name = (isset($name[0])) ? $name[0] : 'NA';
            $last_name = (isset($name[1])) ? $name[1] : 'NA';
        }
        $merchant_id = $profile->profile->id;
        $auth_token = $profile->token;

        $url = 'https://accept.paymobsolutions.com/api/ecommerce/orders?token=' . $auth_token;

        $order_data = [
            "delivery_needed" => "false",
            "merchant_id" => $merchant_id,  // merchant_id obtained from step 1
            "merchant_order_id" => $user_id . rand(1, 5) . str_random(2),  // unique alpha-numerice value, example: E6RR3
            "amount_cents" => $final_total_price * 100,
            "currency" => "EGP",
            "items" => [],
            "shipping_data" => [
                "first_name" => $first_name,
                "phone_number" => ($request->has('phone') && $request->phone != '') ? $request->phone : $user->phone,
                "last_name" => $last_name,
                "email" => ($request->has('email') && $request->email != '') ? $request->email : $user->email,
                "apartment" => 'NA',
                "floor" => 'NA',
                "street" => 'NA',
                "building" => 'NA',
                "postal_code" => 'NA',
                "country" => 'NA',
                "city" => 'NA'
            ]
        ];


        $request_headers = [
            'Content-Type: application/json',
            'Content-Length: ' . strlen(json_encode($order_data))
        ];

        $order = curlPostRequest($url, $order_data, $request_headers);

        if (!isset($order->id)) {
            return $this->respondWithErorr("Error", "Enable to create order!");
        }

        $order_id = $order->id;


        // 3- Payment Key Generation

        $url = 'https://accept.paymobsolutions.com/api/acceptance/payment_keys?token=' . $auth_token;

        $payment_data = [
            "amount_cents" => $final_total_price * 100,
            "currency" => "EGP",
            "order_id" => $order_id,  // order_id_from_step_2
            "card_integration_id" => env('CARD_INTEGRATION_ID'),  // card integration_id will be provided upon signing up,
            "billing_data" => [

                "first_name" => $first_name,
                "phone_number" => ($request->has('phone') && $request->phone != '') ? $request->phone : $user->phone,
                "last_name" => $last_name,
                "email" => ($request->has('email') && $request->email != '') ? $request->email : $user->email,
                "apartment" => 'NA',
                "floor" => 'NA',
                "street" => 'NA',
                "building" => 'NA',
                "postal_code" => 'NA',
                "country" => 'NA',
                "city" => 'NA',
                "shipping_method" => "NA",
                "state" => "NA"
            ],
        ];

        $request_headers = [
            'Content-Type: application/json',
            'Content-Length: ' . strlen(json_encode($payment_data))
        ];

        $payment_key = curlPostRequest($url, $payment_data, $request_headers);

        if (!isset($payment_key->token)) {
            return $this->respondWithErorr("Error", "Enable to do payment!");
        }

        $payment_token = $payment_key->token;

        $iframe_id = env('WE_ACCEPT_IFRAME_ID'); //$iframe->id;

        $iframe_url = 'https://accept.paymobsolutions.com/api/acceptance/iframes/' . $iframe_id . '?payment_token=' . $payment_token;
        $salesOrderController = new SalesOrderController;
        array_merge($request->all(), ['payment_method' => 'Credit']);
        array_merge($request->all(), ['payment_order_id' => $order_id]);

        $checkout = $salesOrderController->checkout($request, 1, $order_id);
        if ($checkout->getContent() && json_decode($checkout->getContent()) && isset(json_decode($checkout->getContent())->Status) && json_decode($checkout->getContent())->Status != '200') {
            return $checkout;
        }
        return $this->respond($iframe_url);
    }

    public function Authentication()
    {
        // 1-  Register
        if (env('WE_ACCEPT_API_KEY')) {
            $data = [
                "api_key" => env('WE_ACCEPT_API_KEY')
            ];
        } else {
            $data = [
                "username" => env("PAYMENT_USERNAME", "mahmoudsaeed"),
                "password" => env("PAYMENT_PASSWORD", "Mah3313652")
            ];
        }



        $payload = json_encode($data);
        $request_headers = [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload)
        ];

        $url = 'https://accept.paymobsolutions.com/api/auth/tokens';
        $profile = curlPostRequest($url, $data, $request_headers);
        return $profile;
    }

    public function callback(\Illuminate\Http\Request $request)
    {
        $onlinePaymentLog = OnlinePaymentLogs::create([
            'callback_response_parameters' => json_encode($request->query())
        ]);
        $success = '';
        $orderId = 0;

        if ($request->has('success') && $request->has('order')) {
            $success = $request->success;
            $orderId = $request->order;
        }

        DB::table('payment_logs')->insert([
            'text' => json_encode(request()->all()),
            'order_id' => $orderId,
            'success' => $success,
        ]);
        $amount_cents = $request->amount_cents / 100;

        $order = Orders::withoutGlobalScopes()->where('payment_order_id', $orderId)->latest()->first();
        if ($order) {
            $onlinePaymentLog->user_id = $order->user_id;
            $onlinePaymentLog->order_id = $order->id;
            $onlinePaymentLog->save();
        }


//        if (!$order) return $this->returnHtmlResponse($amount_cents, false);

        $order_device = \strtolower($order->device_os);

        $validate_hmac = $this->validateCallbackHmac($request);

        if (!$order && ($order_device == 'ios' || $order_device == 'android')) {
            restoreStocks($order, 'all');
            $error_message = $this->weacceptErrorMessageHandler($request['txn_response_code']);

            return redirect()->action('WeAcceptPaymentsController@finalCallBack',
                ['success' => "false", 'data.message' => $error_message, 'acq_response_code' => "44"], 302)
                ->with('message', 'Payment failed!!!');

        } elseif (!$order) {
            restoreStocks($order, 'all');

            return $this->returnHtmlResponse($amount_cents, false);
        }
        // validating hmac response with local key versus exterior manipulation with the response
        if (!$validate_hmac && ($order_device == 'ios' || $order_device == 'android')) {
            restoreStocks($order, 'all');

            return redirect()->action('WeAcceptPaymentsController@finalCallBack', ['success' => "false",
                'data.message' => 'Validation Rejected', 'acq_response_code' => "44"], 302)
                ->with('message', 'Rejected!!!');
        } elseif (!$validate_hmac) /*&& $order_device == 'WEB' or null*/ {
            restoreStocks($order, 'all');

            return $this->returnHtmlResponse($amount_cents, false);
        }


        if ($request['success'] === 'true') {
            if ($order->status != 'Added') {
                $to_name = $order->user->name;
                $to_email = $order->user->email;
                $orderId = $order->id;
                $data = array('name' => $to_name, 'status' => 'Pending', 'number' => $orderId);

                $order_items = $order->allOrderItems;

                if (isset($order_items)) {
                    foreach ($order_items as $item) {
                        $image = getImages($item->item_id);
                        $item->image = $image;
                    }
                }

                try {
                    Mail::send(['html' => 'mail.mailTemplate'], ['body' => $data, 'items' => $order_items],
                        function ($message) use ($to_name, $to_email) {
                        $message->to($to_email, $to_name)
                            ->subject('Order Status - Khotwh');
                        $message->from(env('MAIL_USERNAME'), 'khotwh Fashion');
                    });
                } catch (\Exception $ex) {

                }
                $order->status = 'Pending';
                $order->save();
                $userId = $order->user_id;
                $userCart = Cart::where('user_id', $userId)->latest()->first();
                if ($userCart) {
                    $userCart->CartItems()->delete();
                    $userCart->delete();
                }
                if ($order_device == 'ios' || $order_device == 'android') {
                    return redirect()->action('WeAcceptPaymentsController@finalCallBack',
                        ['success' => "true", 'data.message' => 'Approved',
                        'acq_response_code' => "00"], 302)
                        ->with('message', 'Payment Approved!!!');
                } else {
                    return $this->returnHtmlResponse($amount_cents, true);
                }
            }
        } else {
            restoreStocks($order, 'all');

            if ($order_device == 'ios' || $order_device == 'android') {
                return redirect()->action('WeAcceptPaymentsController@finalCallBack', ['success' => "false",
                    'data.message' => 'Failed',
                    'acq_response_code' => "44"], 302)
                    ->with('message', 'Payment failed!!!');
            } else {
                return $this->returnHtmlResponse($amount_cents, false);
            }
        }

        /* incase the payment failed for any reason
         but the cash was collected we keep track of order so we wont delete the order recode
         */

        // $order->OrderItems()->delete();
        // $order->delete();
//        return $this->returnHtmlResponse($amount_cents, false);
    }

    public function status()
    {
        $token = getTokenFromReq(request());

        $user = \App\User::where('token', '=', $token)->latest()->first();
        if (!$user) return $this->respondAuthError();

        $user_id = $user->id;
        $user_order = UserOrderPayment::where('user_id', $user_id)->first();
        if (!$user_order) return $this->respondNotFound();

        return $this->respond($user_order->status);
    }
    public function validateCallbackHmac($request)
    {
        // hmac secret key
        $secret_key = config('global.weaccept_secret_key');

        // needed key for hashing in alphabetically sorted keys
        $required_params = ['amount_cents', 'created_at', 'currency', 'error_occured', 'has_parent_transaction', 'id',
            'integration_id', 'is_3d_secure', 'is_auth', 'is_capture', 'is_refunded', 'is_standalone_payment', 'is_voided', 'order',
            'owner', 'pending', 'source_data_pan', 'source_data_sub_type', 'source_data_type', 'success',
        ];

        $concated_string = '';
        $hashed_hmac = $request->hmac;
        foreach ($required_params as $param) {
            $concated_string .= $request->has($param) ? $request[$param] : '';
        }

        // start hashing the string
        $hashed_string = hash_hmac('sha512', $concated_string, $secret_key);
        // validate both hashed hmacs are equal using md5
        if (md5($hashed_string) === md5($hashed_hmac)) {
            return true;
        } else {
            return false;
        }

    }

    public function processCallBack(\Illuminate\Http\Request $request)
    {
        Log::useDailyFiles(storage_path() . '/logs/debugUrl.log');
        Log::info('Processing Online payment callback function: ' . json_encode($request));
        $request_body = json_decode(json_encode($request->input()));

        DB::table('payment_logs')->insert([
                'text' => json_encode($request->all()),
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]
        );

    }

    public function finalCallBack(\Illuminate\Http\Request $request)
    {
        if ($request->success == 0) {
            return response()->make();
        } else {
            return response()->make();
        }
    }

    private function weacceptErrorMessageHandler($code)
    {
        $message = '';
        switch ($code) {
            case "1":
                $message = 'There was an error processing the transaction';
                break;
            case "2":
                $message = 'Contact card issuing bank';
                break;
            case "4":
                $message = 'Expired Card';
                break;
            case "5":
                $message = 'Insufficient Funds';
                break;
            case "6":
                $message = 'Payment is already being processed';
                break;
            default:
                $message = 'Payment process failed please try again later!';
        }
        return $message;
    }

    public function returnHtmlResponse($amount_cents, $status)
    {
        $message = ($status) ? 'Thank you for using the online payment service.You have successfully paid EGP ' . $amount_cents . ' to Khotwh' : 'Sorry we were unable to complete the checkout process .. Please try again later';
        return '
 <!doctype html>
 <html lang="en">

<head>
  <meta charset="utf-8">
  <title>khotwh</title>
  <link rel="icon" href="http://163.172.78.65:4031/favicon.ico" type="image/x-icon">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"><style>
html, body{background: #fff;color: #000;font-size: 1rem;overflow-x: hidden;}
.logo{margin-top: 150px;margin-bottom: 50px}
.logo img{width: auto}

.soon{font-size: 1.3rem;color: #5C5C5C;line-height: 1.9;}
.btn-success {padding: 10px 45px;color: #fff;background-color: #213c53;border-color: #213c53;}
.btn-success:hover, .btn-success:focus {box-shadow:none; color: #fff;background-color: #213c53;border-color: #213c53;}
.footer{position: absolute;width: 100%;bottom: 0;height: 50px;}
</style>
</head>

<body>
<div class="col-lg-6 mx-auto text-center pt-2 align-self-center">
<div class="logo">
<img src="http://khotwh.com/assets/images/icon/logo.png">
</div>
  <h4 class="soon">
  ' . $message . '
</h4>

<a class="btn btn-success my-4" href="http://khotwh.com">Continue</a>
</div>

<div class="row mx-0 footer">
    <div class="col-sm-12 text-right pr-lg-5">
      <div class="footer-end">
        <p><i class="fa fa-copyright" aria-hidden="true"></i> © Khotwh powered by
         <a href="http://retailak.com/" target="_black">Retailak</a>.</p>
      </div>
    </div>
    </div>

</body>
</html>
                ';
    }
}

function curlPostRequest($url, $data, $request_headers = [], $hideError = false)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    // Set HTTP Header for POST request
    curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);

    $rest = curl_exec($ch);

    $result = json_decode($rest);

    if (!$hideError) {
        return $result;
    }
}
