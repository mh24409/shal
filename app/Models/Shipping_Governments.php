<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping_Governments extends Model
{
    use HasFactory;
    protected $table = 'shipping_governments';
    protected $fillable = [
        'name',
        'id',
    ];

    public function shipping_cities()
    {
        return $this->hasMany(Shipping_Cities::class, 'government_id', 'id');
    }
}
