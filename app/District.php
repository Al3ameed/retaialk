<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class District extends Model
{

    protected $fillable = ['district_en', 'district_ar', 'parent_id', 'shipping_role', 'active','sortno'];

    public function adjustments()
    {
        return $this->belongsToMany(AdminUser::class, 'adjustments', 'content_id', 'user_id')->withPivot('key', 'action')->withTimestamps()->latest('pivot_updated_at');
    }

    public function shipping()
    {
        return $this->belongsToMany(ShippingRule::class,'district_shipping_rules',  'district_id', 'shipping_rule_id')
            ->withPivot('from_weight','to_weight');
    }
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('sortno', function (Builder $builder) {
            $builder->orderBy('sortno',  'asc');
        });

    }
}
