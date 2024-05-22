<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Orders extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    public function userOrder(){
        return  $this->hasOne(User::class,'id','user_id');
    }

    public function orderItems(){
        return  $this->hasMany(OrderItems::class,'order_id','id');
    }
    public function vendorName(){       
        return  $this->belongsTo(User::class,'vendor_id','id');
    }
     public function shippingAddress(){       
          return  $this->hasOne(OrderAddresses::class,'order_id','id')->where('type',0);
    }
    public function billingAddress(){       
          return  $this->hasOne(OrderAddresses::class,'order_id','id')->where('type',1);
    }
}
