<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
    protected $fillable = ['name', 'description','discussion','quality', 'long_slug', 'description_to_store', 'lang', 'category_id'];

    public function category(){
    	return $this->belongsTo(Category::class);
    }
}
