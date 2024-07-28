<?php

namespace App\Http\Requests\Product;

use App\Enums\ProductStatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
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
        $status = [ProductStatusEnum::ACTIVE->value, ProductStatusEnum::INACTIVE->value];

        return [
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string|max:255',
            'price'        => 'required|numeric',
            'status'       => 'required|in:' . implode(',', $status),
            'categories'   => 'required|array',
            'categories.*' => 'integer|exists:categories,id',
        ];
    }
}
