<?php

namespace Modules\CmsMobile\Entities;

use Illuminate\Database\Eloquent\Model;

class logCms extends Model
{
    protected $table ='cms_log_error';
    protected $fillable = ['message','order_id'];
}
