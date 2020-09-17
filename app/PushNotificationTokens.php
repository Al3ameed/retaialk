<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PushNotificationTokens extends Model
{
    protected $fillable = ['device_token','user_id','push_notification_id'];
}
