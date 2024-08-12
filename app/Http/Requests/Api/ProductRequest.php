<?php

namespace App\Http\Requests\Api;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'min:3', 'max:250'],
            'description' => ['required', 'min:5', 'max:255'],
            'price' => ['required','numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'files' => ['array', 'min:1'],
            'files.*' => [
                'bail',
                'image',
                'mimetypes:image/jpeg,image/png,image/webp',
                'max:10048',
            ],
        ];
    }
}
