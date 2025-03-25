<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'user_id', 'type', 'code','details','discount', 'is_limited','is_user_limit','users_limit','num_of_uses','uses_limit','discount_type', 'start_date', 'lifetime','end_date','description'
    ];

    public function user(){
    	return $this->belongsTo(User::class);
    }
}
