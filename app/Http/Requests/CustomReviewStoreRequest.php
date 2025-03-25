<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomReviewStoreRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'names'=>'required|string',
            'comments'=>'required|string',
            'date_rang'=>'required|string'
            ];
    }
    
    
}
