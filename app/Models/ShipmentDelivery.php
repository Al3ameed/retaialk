<?php

namespace App\Models;

use App\Orders;
use Illuminate\Database\Eloquent\Model;

class ShipmentDelivery extends Model
{
    protected $table = 'shipment';
    protected $fillable = ['order_id','shipment_id', 'response', 'has_error', 'transaction', 'notification', 'label_url', 'shipment_track','status' , 'shipment_company' , 'trackingNumber'];
    public $timestamps = true;
    // order relationship
    public function order (){
        return $this->belongsTo(Orders::class , 'order_id' , 'id');
    }
    public function status () {
        return $this->hasMany(ShipmentLog::class , 'delivery_id' , 'shipment_id');
    }

    public function latestStatus()
    {
        return $this->hasOne(ShipmentLog::class , 'delivery_id' , 'shipment_id')->orderBy('created_at', 'desc');
    }
}
