<?php

namespace Modules\Students\src\Http\Requests\Clients;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Http\FormRequest;

class PasswordRequest extends FormRequest
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
        $rules = [
            'old_password' => ['required', function ($attribute, $value, $fail) {
                $status = Hash::check($value, Auth::guard('students')->user()->password);
                if (!$status) {
                    $fail(__('Students::validation.password-invalid'));
                }
            }],
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'required' => __('Students::validation.required'),
            'min' => __('Students::validation.min'),
        ];
    }

    public function attributes()
    {
        return [
            'old_password' => __('Students::validation.attributes.old_password'),
            'password' => __('Students::validation.attributes.password'),
            'confirm_password' => __('Students::validation.attributes.confirm_password'),
        ];
    }
}
