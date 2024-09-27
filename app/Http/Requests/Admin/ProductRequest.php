<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        if ($this->method() == 'POST') {
            return [
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'quantity' => 'required|integer|min:0',
            ];

        }

        return [
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|integer|min:0',
        ];

    }
}
