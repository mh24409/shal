<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'state_id'=> 'required_without:address_id',
            'country_id'=>'required',
        ];
    }
    public function messages(){
        return [
            'state_id.required'=>translate('state is required'),
            'country_id.required'=>translate('wrong request detected'),
            // 'phone.required'=>translate('phone is required'),
            // 'phone.regex'=>translate('phone must be 10 numbers')
        ];
    }
}
