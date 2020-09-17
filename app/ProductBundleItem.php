<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductBundleItem extends Model
{
    protected $fillable = ['bundle_id','item_id','qty'];
    protected $table = 'product_bundle_items';
   
}
