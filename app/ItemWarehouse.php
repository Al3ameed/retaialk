<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemWarehouse extends Model
{
    protected $table = "products_warehouses";
    protected $fillable = ['product_id','warehouse_id','projected_qty','item_code'];
}
