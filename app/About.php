<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class about extends Model
{
    //
    protected $table= 'about';
    public $timestamps = false;

    protected $fillable= ['id', 'content', 'video'];
}
