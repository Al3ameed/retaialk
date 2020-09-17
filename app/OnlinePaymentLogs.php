<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OnlinePaymentLogs extends Model
{
    //
    protected $table = 'online_payment_logs';

    protected $fillable = ['order_id', 'user_id', 'callback_response_parameters'];
}
