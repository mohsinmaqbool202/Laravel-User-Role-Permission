<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Customer extends Model
{

    use HasApiTokens;

    protected $fillable = ['name','email', 'password','verified'];

     public function deliveryAddress()
    {
        return $this->hasOne('App\DeliveryAddress');
    }
}
