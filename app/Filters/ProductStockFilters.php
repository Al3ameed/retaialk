<?php

namespace App\Filters;

class ProductStockFilters extends Filters
{
	protected $filters = ['price', 'item_group', 'food_extras', 'barcode'];



	public function item_group($item_group){
	    if (isset($item_group) && $item_group !== null) {
            return $this->builder->where('item_group',$item_group);
        }
	}

    public function price($price){
	    if (isset($price) && $price !== null) {
            return $this->builder->where('standard_rate',$price);
        }
    }

    public function item_code($barcode){
	    if ($barcode !== null) {
            return $this->builder->where('item_code',$barcode);
        }
    }
}
