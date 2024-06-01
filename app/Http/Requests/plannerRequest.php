<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class plannerRequest extends FormRequest
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
            'task'=>'required',
            'dtask'=>'required',
            'ttask'=>'required'
        ];
    }

    public function messages()
    {
        return [
            'task.required'=>'You haven\'t entered a task.',
            'dtask.required'=>'You haven\'t entered a date.',
            'ttask.required'=>'You haven\'t entered a time.'
        ];
    }
}
