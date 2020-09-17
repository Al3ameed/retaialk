<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CartItems;

class Cart extends Model
{
	protected $fillable=['user_id'];
    protected $table = "cart";


    public function CartItems(){
        return $this->hasMany(CartItems::class,'cart_id')->whereHas('product', function($query) {
            $query->where('active', 1)->whereHas('parent', function($query) {
                $query->where('active', 1);
            });
        });
    }

    public function items(){
        return $this->hasMany(CartItems::class,'cart_id')->whereHas('product', function($query) {
            $query->where('active', 1)->whereHas('parent', function($query) {
                $query->where('active', 1);
            });
        });
    }
}
