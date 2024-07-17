<?php

namespace Modules\Students\src\Http\Requests\Clients;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
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
        $id = Auth::guard('students')->user()->id;
        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:Students,email,' . $id,
            'phone' => 'required|regex:/(0)[0-9]{9}/',
            'address' => 'required',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'required' => __('Students::validation.required'),
            'email' => __('Students::validation.email'),
            'unique' => __('Students::validation.unique'),
            'min' => __('Students::validation.min'),
            'phone.regex' => __('Students::validation.phone-invalid'),
        ];
    }

    public function attributes()
    {
        return [
            'name' => __('Students::validation.attributes.name'),
            'email' => __('Students::validation.attributes.email'),
            'password' => __('Students::validation.attributes.password'),
            'phone' => __('Students::validation.attributes.phone'),
            'address' => __('Students::validation.attributes.address'),
        ];
    }
}
