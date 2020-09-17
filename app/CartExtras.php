<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CartExtras extends Model
{
	protected $fillable = ['cart_item_id','item_id','extra_id','qty'];  
}
