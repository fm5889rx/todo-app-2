<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

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
        return [
            'name' => 'required|string|max:10|unique:categories'
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'name.required' => 'カテゴリーを入力してください',
            'name.string' => 'カテゴリーは文字列でなければなりません',
            'name.max' => 'カテゴリーを10文字以内で入力してください',
            'name.unique' => 'このカテゴリーはすでに存在しています',
        ];
    }
}
