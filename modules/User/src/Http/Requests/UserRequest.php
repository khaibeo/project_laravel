<?php

namespace Modules\User\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'group_id' => ['required','integer',function($attribute, $value, $fail){
                if($value == 0 ){
                    $fail('Hãy chọn nhóm người dùng');
                }
            }],
        ];
    }

    public function messages()
    {
        return [
            'required' => ":attribute bắt buộc phải nhập",
            'string' => ":attribute phải là chuỗi",
            'max' => ":attribute tối đa :max kí tự",
            'email' => "Email không đúng định dạng",
            'unique' => ":attribute đã tồn tại",
            'min' => ":attribute phải tối thiểu :min kí tự",
            'integer' => ":attribute phải là số"
        ];
    }

    public function attributes()
    {
        return [
            'name' => "Họ và tên",
            'email' => "Email",
            'password' => "Mật khẩu",
            'group_id' => "Nhóm"
        ];
    }
}
