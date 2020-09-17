<?php

namespace App\Http\Controllers\Shipment;

use App\Addresses;
use App\Http\Controllers\Controller;
use App\Models\ShipmentDelivery;
use App\Models\ShipmentLog;
use App\Orders;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Log;

class ShipmentController extends Controller
{
    const SHIPPMENT_COMPANY = 'bosta';
    public function createShipment()
    {
        $orderId = request()->id;
        $order = Orders::findOrFail($orderId);

        $user = User::where('id', $order->user_id)->first();
        $user_address = Addresses::where('user_id', $order->user_id)->first();
        if (!isset($order->items)) {
            return redirect('admin/sales-orders')->with('error', 'There is no items.');
        }
        try {
            $item_descriptions = '';
            foreach ($order->items as $item) {
                $item_descriptions .= $item->qty . " ".$item->product->name_en." ,";
            }
            $client = new Client([
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => config('global.bosta_token'),
                ],
                'base_uri' => config('global.bosta_url'),
            ]);
            // webhook url called by the third party apis (bosta) to update delivery status
            $webHookUrl = url('/api/shipment/webhook');
            $receiver = [
                "firstName" => $user->name ? $user->name : 'N/A',
                "lastName" => $user->name ? $user->name : 'N/A',
                "phone" => $user->phone ? $user->phone : 'N/A',
                "email" => $user->email ? $user->email : 'hello@khotwh.com',
            ];
            // pickup Address
            $pickupAddress = [
                'firstLine' => 'Giza , Haram , Nasir El thawra , Ibrahim El gabri',
                'floor' => 1,
                'apartment' => 1,
                'zone' => 'Haram',
                'district' => 'Giza',
            ];
            $user_dropoffaddress = "";
            $user_dropoffaddress .= isset($user_address->title) ? $user_address->title . ", " : "";
            $user_dropoffaddress .= isset($user_address->street) ? $user_address->street . ", " : "";
            $user_dropoffaddress .= isset($user_address->district) ? $user_address->district->district_en : "";
            // Drop off Address
            $dropOffAddress = [
                'firstLine' => $user_dropoffaddress, //isset($user_address->title) ? $user_address->title : ($user_address->street . '- ' . $user_address->nearest_landmark),
                'secondLine' => $user_address->street . '- ' . $user_address->nearest_landmark,
                'floor' => isset($user_address->floor_no) ? $user_address->floor_no : 'N/A',
                'apartment' => isset($user_address->apartment_no) ? $user_address->apartment_no : 'N/A',
                'zone' => isset($user_address->city) ? $user_address->city : 'NA',
                'district' => isset($user_address->district) ? $user_address->district->district_en : 'N/A',
            ];

            // collect all data in body object
            $body = [
                "receiver" => $receiver,
                'pickupAddress' => $pickupAddress,
                'dropOffAddress' => $dropOffAddress,
                'returnAddress' => $pickupAddress,
                'notes' => $item_descriptions,
                'type' => 15,
                "webhookUrl" => config('global.webhook_url'),
            ];

            $body['cod'] = $order->total_price + $order->shipping_rate - $order->discount;

            $response = $client->post('deliveries', [
                'body' => json_encode($body),
            ]);

            $body = json_decode($response->getBody()->getContents());

            // save Data in DataBase
            try {
                $shipment = new shipmentDelivery();
                $shipment->order_id = $orderId;
                $shipment->shipment_id = $body->_id;
                $shipment->trackingNumber = $body->trackingNumber;
                $shipment->status = $body->message;
                $shipment->shipment_company = self::SHIPPMENT_COMPANY;
                $shipment->save();
                $this->saveShipmentReceipt($shipment);
            } catch (\Exception $ex) {
                Log::info('save bosta shipment to db', [$ex]);
            }
            return redirect('/admin/sales-orders')
                ->with('success', 'Create an Delivery shipment Successfully.');
        } catch (\Exception $exception) {
            $response = null;
            if ($exception->getResponse()) {
                $response = $exception->getResponse();
                $responseBodyAsString = json_decode($response->getBody()->getContents());
            }

            $errors = (isset($responseBodyAsString) && isset($responseBodyAsString->message)) ? $responseBodyAsString->message : $exception->getMessage();
            dd($responseBodyAsString);
            return redirect('admin/sales-orders')->withErrors($errors);
        }
    }

    public function webHookDelivery(Request $request)
    {

        $validator = Validator::make($request->all(), [
            '_id' => 'required|string|exists:shipment,shipment_id',
            'state' => 'required|numeric',
            'starName' => 'string',
            'cod' => 'numeric',
            'exceptionReason' => 'string',
            'price' => 'numeric',
            'weight' => 'numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $shpment_logs = new ShipmentLog();
        $shpment_logs->delivery_id = $request->_id;
        $shpment_logs->state = $request->state;
        $shpment_logs->starName = $request->starName;
        $shpment_logs->cod = $request->cod;
        $shpment_logs->exceptionReason = $request->exceptionReason;
        $shpment_logs->price = $request->price;
        $shpment_logs->weight = $request->weight;
        $check = $shpment_logs->save();

        if ($check) {
            return response()->json('shipment log has been saved successfully', 200);
        } else {
            return response()->json('something went wrong', 400);
        }
    }

    public function shipmentTracking()
    {
        // check data
        $shipmentId = request()->id;
        $shipment = ShipmentDelivery::where('shipment_id', $shipmentId)->first();
        if (!$shipment) {
            return redirect('admin/sales-orders')->withErrors(['this order is not exists any more']);
        }
        $shipment->order;
        $body = [];
        $type = 1;
        // check shipment type
        if ($shipment->shipment_company == self::SHIPPMENT_COMPANY) {
            // call api to get order status
            $client = new Client([
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => config('global.bosta_token'),
                ],
                'base_uri' => config('global.bosta_url'),
            ]);

            $response = $client->get('deliveries/' . $shipmentId . '/state-history');
            $body = json_decode($response->getBody()->getContents());
        } else {
            $type = 0;
        }
        return view('admin.shipment.track', [
            'tracking' => (array) $body,
            'shipment' => $shipment,
            'type' => $type,
        ]);

    }

    public function getShipmentReceipts(Request $request)
    {
        $orderIds = $request->input('order_ids');

        $list = [];
        $shipmentReceipts = shipmentDelivery::whereIn('order_id', $orderIds)->get();
        foreach ($shipmentReceipts as $receipt) {
            if (!is_null($receipt->label_url)) {
                array_push($list, $receipt->label_url);
            }
        }

        return response()->json($list, 200);
    }

    private function saveShipmentReceipt($shipment)
    {
        try {
            $client = new Client([
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => config('global.bosta_token'),
                ],
                'base_uri' => config('global.bosta_url'),
            ]);

            $response = $client->get('deliveries/awb/' . $shipment->shipment_id);

            $body = json_decode($response->getBody()->getContents());


            // TODO: continue the process to get base64 pdf file and store it locally
            $bin_pdf = base64_decode($body->data, true);
            $fileName = 'bosta_receipt_'.rand(1, 1000000).'.pdf';
            $path = public_path('bostaShipmentFiles');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $file = $path . '/'.$fileName;
            file_put_contents($file, $bin_pdf);
            $savedURL = url('/public/bostaShipmentFiles/'. $fileName);
            $shipment->label_url = $savedURL;
            $shipment->save();
        } catch (\Exception $ex) {
            Log::info('save bosta shipment pdf', [$ex]);

        }
    }
}
