<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class expenseRequest extends FormRequest
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
            'appointment'=>'required',
            'amount'=>'required',
        ];
    }

    public function messages()
    {
        return [
            'appointment.required'=>'You haven\'t entered a appointment.',
            'amount.required'=>'You haven\'t entered an amount.'
        ];
    }
}
