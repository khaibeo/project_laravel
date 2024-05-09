<?php

namespace Modules\Category\src\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
        $rules = [
            'name' => 'required|max:255',
            'slug' => 'required',
            'parent_id' => ['required','integer']
        ];
        
        return $rules;
    }

    public function messages()
    {
        return [
            'required' => ":attribute bắt buộc phải nhập",
            'max' => ":attribute tối đa :max kí tự",
            'integer' => ":attribute phải là số"
        ];
    }

    public function attributes()
    {
        return [
            'name' => "Tên nhóm",
            'slug' => "Slug",
            'parent_id' => "Cha"
        ];
    }
}
