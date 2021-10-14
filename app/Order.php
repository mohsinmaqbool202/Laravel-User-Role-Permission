<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function user(){
        return $this->belongsTo('App\Customer');
    }

    public function orders(){
        return $this->hasMany('App\OrderProduct', 'order_id');
    }
}
