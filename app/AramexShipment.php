<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AramexShipment extends Model
{
    protected $fillable = ['order_id','shipment_id', 'response', 'has_error', 'transaction', 'notification', 'label_url', 'shipment_track','status', 'shipment_company'];

    protected $table = 'shipment';
}
