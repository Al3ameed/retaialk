<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentLog extends Model
{
    protected $table = 'shipment_logs';
    protected $fillable = ['id', 'delivery_id', 'state', 'starName', 'cod', 'exceptionReason', 'price', 'weight', 'created_at', 'updated_at'];
    public $timestamps = true;
}
