<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesInvoice extends Model
{
    protected $fillable=['delivery_note_id','order_id','productlist','date','shipping_role_id','user_id','delivery_note_id','delivery_order_id','status'];

}
