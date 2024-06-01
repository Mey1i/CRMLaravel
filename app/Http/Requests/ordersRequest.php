<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ordersRequest extends FormRequest
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
            'product_id'=>'required',
            'client_id'=>'required',
            'quantity'=>'required'
        ];
    }

    public function messages()
    {
        return [
            'client_id.required'=>'You haven\'t choose a client',
            'quantity.required'=>'You haven\'t entered a quantity.'
        ];
    }
}
