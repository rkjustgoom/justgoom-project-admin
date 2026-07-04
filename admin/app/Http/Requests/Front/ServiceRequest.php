<?php

namespace App\Http\Requests\Front;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'product_name' => trim((string) $this->input('product_name', '')),
            'product_desc' => $this->filled('product_desc') ? trim((string) $this->input('product_desc')) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'type' => ['nullable', 'in:service,product'],
            'product_name' => ['required', 'string', 'min:2', 'max:200'],
            'product_desc' => ['nullable', 'string', 'max:5000'],
            'product_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
            'price' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_name.required' => 'Service name is required.',
            'product_name.min' => 'Service name must be at least 2 characters.',
            'product_name.max' => 'Service name must not exceed 200 characters.',
            'product_desc.max' => 'Description must not exceed 5000 characters.',
            'product_image.image' => 'Service image must be JPG, PNG, WebP, or GIF.',
            'product_image.mimes' => 'Service image must be JPG, PNG, WebP, or GIF.',
            'product_image.max' => 'Service image must not be larger than 2 MB.',
        ];
    }

    public function attributes(): array
    {
        return [
            'product_name' => 'service name',
            'product_desc' => 'description',
            'product_image' => 'service image',
        ];
    }
}
