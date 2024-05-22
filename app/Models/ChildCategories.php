<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChildCategories extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * table name.
     *
     * @var string
     */
    protected $table = 'child_category';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'cat_id',
        'child_id'
    ];

    public function parent_categories() {
        return $this->hasMany(ProductCategory::class, 'id', 'cat_id');
    }
}
