<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTranslation extends Model
{
    protected $fillable = ['product_id', 'name', 'video_name', 'design', 'event', 'fabric_type','close_type','hand_type','unit', 'description','long_description' , 'lang'];

    public function product(){
      return $this->belongsTo(Product::class);
    }
}
