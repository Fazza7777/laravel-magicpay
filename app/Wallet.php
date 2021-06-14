<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = ['user_id','account_number','amount'];
    // or
    // protected $guarded = [];
}
