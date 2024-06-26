<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class positionsRequest extends FormRequest
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
            'department_id'=>'required',
            'position'=>'required'
        ];
    }

    public function messages()
    {
        return [
            'department_id.requried'=>'You haven\'t choosed a department',
            'position.required'=>'You haven\'t entered a position.'
        ];
    }
}
