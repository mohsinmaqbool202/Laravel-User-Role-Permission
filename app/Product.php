<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
   
    protected $fillable = [
        'name',
        'code',
        'color',
        'price', 
        'detail',
        'image',
        'status'
    ];

    public function images()
    {
        return $this->hasMany('App\ProductImages');
    }
}
