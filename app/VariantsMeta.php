<?php

namespace App;

use App\Variations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VariantsMeta extends Model
{
    use SoftDeletes;

    protected $fillable = ['variation_value_en', 'variation_value', 'variant_data_id', 'code', 'item_code', 'variant_code'];

    public function variantData()
    {
        return $this->belongsTo(Variations::class, 'variant_data_id');
    }

}
