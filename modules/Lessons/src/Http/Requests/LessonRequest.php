<?php

namespace Modules\Lessons\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LessonRequest extends FormRequest
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
        $rules =  [
            'name' => 'required|max:255',
            'slug' => 'required|max:255',
            'parent_id' => 'required|integer',
            'is_trial' => 'required|integer',
            'position' => 'required|integer',

        ];

        // dd($this->parent_id);
        if ($this->parent_id != 0) {
            $rules['video'] = 'required';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'required' => ':attribute bắt buộc phải nhập',
            'email' => ':attribute không đúng định dạng',
            'integer' => ':attribute phải là số',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Tên',
            'slug' => 'Slug',
            'parent_id' => 'Nhóm bài giảng',
            'is_trial' => 'Học thử',
            'position' => 'Thứ tự',
            'video' => 'Video',
            'document' => 'Tài liệu',
            'description' => "Mô tả",
        ];
    }
}
