<?php

namespace Modules\CmsMobile\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CmsMobile\Entities\logCms;

class CmsMobileController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($data,$order_id)
    {
        if($data['user_mobile'] !=null || $data['user_mobile'] !=0 || $data['user_mobile']!='' ) {
            $options = array(
                'cache_wsdl' => 0,
            );
            $userName = config('cmsmobile.cms_username');
            $Password = config('cmsmobile.cms_password');
            $SMSLang = $data['lang'];
            $SMSText = ($SMSLang=='e')?config('cmsmobile.SMSText_en'):config('cmsmobile.SMSText_ar');
            $SMSSender = config('cmsmobile.SMSSender');
            //Mobile Number
            $SMSReceiver = $data['user_mobile'];
            try {
                $opts = array(
                    "UserName" => $userName,
                    "Password" => $Password,
                    "SMSText" => 'text',
                    "SMSLang" => $SMSLang,
                    "SMSSender" => $SMSSender,
                    "SMSReceiver" => $SMSReceiver);
                $context = ($opts);
                $soapClientOptions = array(
                    'stream_context' => $context,
                    'cache_wsdl' => WSDL_CACHE_NONE
                );
                $headerbody = array('UserName'=>$userName,'Password'=>$Password);

                $client =  new \SoapClient("https://smsvas.vlserv.com/KannelSending/service.asmx?WSDL",$headerbody);
                $result = $client->__soapCall("SendSMS", array($opts));
                $code = $result->SendSMSResult;
                $response = $this->CodeResponse($code);
                if (!is_int($response)) {
                    $log = new logCms();
                    $log->message = $response;
                    $log->order_id = $order_id;
                    $log->save();
                }
            }
            catch(Exception $e) {
                echo $e->getMessage();
            }


        }
    }

    public function objectToArray($d)
    {
        $cost_example = array();

        if (is_object($d)) {
            $d = get_object_vars($d);
        }
        if (is_array($d)) {
            return array_map(__FUNCTION__, $d);
        } else {
            return $d;
        }
    }

    private function CodeResponse($code)
    {
        switch ($code) {
            case 0:
                $message = 1;
                break;
            case -1:
                $message = "User is not subscribed";
                break;
            case -5:
                $message = "out of credit.";
                break;
            case -10:
                $message = "Queued Message, no need to send it again.";
                break;
            case -11:
                $message = "Invalid language.";
                break;
            case -12:
                $message = "SMS is empty.";
                break;
            case -13:
                $message = "Invalid fake sender exceeded 12 chars or empty.";
                break;
            case -25:
                $message = "Sending rate greater than receiving rate (only for send/receive accounts).";
                break;
            case -100:
            default:
                $message = "other error.";
                break;
        }
        return $message;
    }
}
