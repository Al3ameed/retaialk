<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reset_Passwrod extends Model
{
    protected $table ="reset_password";

    protected $fillable = ['email', 'token'];

}
