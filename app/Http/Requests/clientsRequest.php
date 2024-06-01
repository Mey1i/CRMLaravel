<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class clientsRequest extends FormRequest
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
            'name'=>'required',
            'surname'=>'required',
            'email'=>'required',
            'telephone'=>'required',
            'company'=>'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required'=>'You haven\'t entered a name.',
            'surname.required'=>'You haven\'t entered a surname.',
            'email.required'=>'You haven\'t entered a email.',
            'telephone.required'=>'You haven\'t entered a telephone.',
            'company.required'=>'You haven\'t entered a company.'
        ];
    }
}
