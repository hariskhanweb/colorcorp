<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartItems extends Model
{
     use SoftDeletes;
     protected $table = 'cart_items'; 


    public function getCartProduct(){       
        return  $this->belongsTo(Product::class,'product_id','id');
    }
}
