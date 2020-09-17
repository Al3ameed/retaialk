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

class MylerzController extends Controller
{
    const SHIPPMENT_COMPANY = 'mylerz';
    public function createShipment()
    {
        $orderId = request()->id;
        $order = Orders::findOrFail($orderId);

        if (!isset($order->items)) {
            return redirect('admin/sales-orders')->with('error', 'There is no items.');
        }
        try {
            // save Data in DataBase
            try {
                $shipment = new shipmentDelivery();
                $shipment->order_id = $orderId;
                $shipment->shipment_id = '';
                $shipment->trackingNumber = '';
                $shipment->status = '';
                $shipment->shipment_company = self::SHIPPMENT_COMPANY;
                $shipment->save();
            } catch (\Exception $ex) {
                Log::info('save bosta shipment to db', [$ex]);
            }
            return redirect('/admin/sales-orders')
                ->with('success', 'Create an Delivery shipment Successfully.');
        } catch (\Exception $exception) {
            return redirect('admin/sales-orders')->withErrors($exception->getMessage());
        }
    }

    public function shipmentTracking()
    {
        return redirect()->back();

    }

}
