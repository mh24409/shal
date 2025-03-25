<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App;

class CustomQuestion extends Model
{
    protected $guarded = [];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
