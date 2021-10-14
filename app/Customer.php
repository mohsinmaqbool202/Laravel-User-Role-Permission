<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name','email', 'password','verified'];

     public function deliveryAddress()
    {
        return $this->hasOne('App\DeliveryAddress');
    }
}
