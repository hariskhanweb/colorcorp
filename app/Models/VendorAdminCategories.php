<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorAdminCategories extends Model
{
    use HasFactory;

    /**
     * table name.
     *
     * @var string
     */
    protected $table = 'vendor_admin_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'vendor_id',
        'status'
    ];

}
