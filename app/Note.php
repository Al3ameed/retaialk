<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    //
    public function order()
    {
        return $this->hasOne('App\Orders', 'order_id');
    }
}
