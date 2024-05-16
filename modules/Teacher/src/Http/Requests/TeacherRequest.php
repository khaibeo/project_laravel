<?php

namespace Modules\Teacher\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherRequest extends FormRequest
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
        $id = $this->route()->user;

        $rules = [
            'name' => 'required|max:255',
            'slug' => 'required|max:255',
            'description' => 'required',
            'exp' => 'required|integer',
            'image' => 'required|max:255',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'required' => ':attribute bắt buộc phải nhập',
            'max' => ':attribute phải từ :max ký tự',
            'integer' => ':attribute phải là số',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Tên',
            'slug' => 'Đường dẫn',
            'description' => 'Mô tả',
            'exp' => 'Kinh nghiệm',
            'image' => 'Ảnh'
        ];
    }
}
