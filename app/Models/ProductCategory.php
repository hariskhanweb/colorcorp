<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Traits\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends \TCG\Voyager\Models\Category
{
	use HasFactory;
    use SoftDeletes;
    
	public function child_categories() {
		return $this->hasMany(ChildCategories::class, 'child_id', 'id');
	}

	public function parent_categories() {
        return $this->hasMany(ProductCategory::class, 'id', 'cat_id');
    }
}