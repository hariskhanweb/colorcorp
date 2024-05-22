<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderAddresses extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'order_addresses'; 

    public function stateName(){       
        return  $this->hasOne(State::class,'id','state_id');
    }

    public function countryName(){       
        return  $this->hasOne(Country::class,'id','country_id');
    }
}
