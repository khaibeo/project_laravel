<?php

namespace Modules\Courses\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CoursesRequest extends FormRequest
{
    /**
     * Determine if the Courses is authorized to make this request.
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
        $id = $this->route()->course;

        $uniqueRule = 'unique:courses,code';

        if ($id) {
            $uniqueRule .= ',' . $id;
        }

        $rules = [
            'name' => 'required|max:255',
            'slug' => 'required|max:255',
            'detail' => 'required',
            'teacher_id' => ['required', 'integer', function ($attribute, $value, $fail) {
                if ($value == 0) {
                    $fail('Bắt buộc phải chọn giảng viên');
                }
            }],
            'thumbnail' => 'required|max:255',
            'code' => 'required|max:255|' . $uniqueRule,
            'is_document' => 'required|integer',
            'supports' => 'required',
            'status' => 'required|integer',
            'categories' => 'required'
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'required' => ':attribute bắt buộc phải nhập',
            'email' => ':attribute không đúng định dạng',
            'unique' => ':attribute đã tồn tại',
            'min' => ':attribute phải từ :min ký tự',
            'max' => ':attribute không được quá :max ký tự',
            'integer' => ':attribute phải là số',
            'select' => ':attribute bắt buộc phải chọn',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Tên',
            'slug' => 'Slug',
            'teacher_id' => 'Giảng viên',
            'code' => 'Mã khóa học',
            'thumbnail' => 'Ảnh đại diện',
            'is_document' => 'Tài liệu đính kèm',
            'supports' => 'Hỗ trợ',
            'status' => "Trạng thái",
            'detail' => 'Nội dung',
            'categories' => "Chuyên mục"
        ];
    }
}
