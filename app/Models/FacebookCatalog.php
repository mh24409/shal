<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacebookCatalog extends Model
{
    use HasFactory;
    protected $guarded =[];
    protected $table = "facebook_catalog";
    public $timestamps =false ;
    protected $casts = [
        'categories' => 'json',
        'products' => 'json',
    ];

}
