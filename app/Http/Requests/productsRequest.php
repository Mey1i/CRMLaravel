<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class productsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'brand_id'=>'required',
            'name'=>'required',
            'purchase'=>'required',
            'sale'=>'required',
            'quantity'=>'required'
        ];
    }

    public function messages()
    {
        return [
            'brand_id.required'=>'You haven\'t choosed a brand',
            'name.required'=>'You haven\'t entered a name.',
            'purchase.required'=>'You haven\'t entered a purchase.',
            'sale.required'=>'You haven\'t entered a sale.',
            'quantity.required'=>'You haven\'t entered a quantity.'
        ];
    }
}
