<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItems extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    public function orderProduct(){
        return  $this->hasOne(Product::class,'id','product_id');
    } 
    
    public function productImage(){
        return  $this->hasOne(ProductMedias::class,'product_id','product_id')->where('is_featured',1);
    }
    public function orderItemsAttribute(){
        return  $this->hasMany(OrderItemAttributes::class,'order_item_id','id');
    }
}
