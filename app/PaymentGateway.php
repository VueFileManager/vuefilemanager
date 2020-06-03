<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    protected $guarded = ['id'];

    public $timestamps = false;
}
