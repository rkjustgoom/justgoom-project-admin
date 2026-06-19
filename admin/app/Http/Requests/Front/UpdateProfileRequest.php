<?php

namespace App\Http\Requests\Front;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'min:4', 'max:200'],
            'category_id' => ['required', 'exists:categories,id'],
            'sub_category_id' => ['required', 'exists:sub_categories,id'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'business_desc' => ['required', 'string', 'min:20', 'max:5000'],
            'phone' => ['required', 'string', 'regex:/^\d{10}$/'],
            'email' => ['required', 'email', 'max:191', Rule::unique('users', 'email')->ignore($this->user()->id)],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Mobile number must be exactly 10 digits.',
            'business_desc.min' => 'About business must be at least 20 characters.',
            'logo.image' => 'Logo must be an image file.',
            'logo.max' => 'Logo must not be larger than 2 MB.',
        ];
    }
}
