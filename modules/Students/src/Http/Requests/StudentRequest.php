<?php

namespace Modules\Students\src\Http\Requests;

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
        $id = $this->route()->student;

        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:students,email',
            'password' => 'required|min:6',
            'status' => ['required', 'integer', function ($attribute, $value, $fail) {
                if ($value != 0 && $value != 1) {
                    $fail(__('Students::validation.select'));
                }
            }],
        ];

        if ($id) {
            $rules['email'] = 'required|email|unique:students,email,' . $id;
            if ($this->password) {
                $rules['password'] = 'min:6';
            } else {
                unset($rules['password']);
            }
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'required' => __('Students::validation.required'),
            'email' => __('Students::validation.email'),
            'unique' => __('Students::validation.unique'),
            'min' => __('Students::validation.min'),
            'integer' => __('Students::validation.integer')
        ];
    }

    public function attributes()
    {
        return [
            'name' => __('Students::validation.attributes.name'),
            'email' => __('Students::validation.attributes.email'),
            'password' => __('Students::validation.attributes.password'),
            'status' => __('Students::validation.attributes.status'),
        ];
    }
}
