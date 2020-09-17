<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    protected $table = "product_variations";
    protected $fillable = ['product_id', 'variant_option_id'];
}
