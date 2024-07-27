<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryUpdateRequest extends FormRequest
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
        $categoryId = $this->route('category');
        $parentId = $this->input('parent_id');

        return [
            'name' => [
                'required',
                'string',
                'max:55',
                Rule::unique('categories')->where(function ($query) use ($categoryId, $parentId) {
                    return $query->where('parent_id', $parentId)
                        ->where('id', '!=', $categoryId);
                }),
            ],
            'parent_id' => 'nullable|integer|exists:categories,id',
        ];
    }
}
