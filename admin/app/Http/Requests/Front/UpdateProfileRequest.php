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

    protected function prepareForValidation(): void
    {
        $this->merge([
            'company_name' => trim((string) $this->input('company_name', '')),
            'tagline' => $this->filled('tagline') ? trim((string) $this->input('tagline')) : null,
            'business_desc' => trim((string) $this->input('business_desc', '')),
            'phone' => preg_replace('/\D+/', '', (string) $this->input('phone', '')),
            'email' => strtolower(trim((string) $this->input('email', ''))),
            'address' => $this->filled('address') ? trim((string) $this->input('address')) : null,
            'city' => trim((string) $this->input('city', '')),
        ]);
    }

    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'min:4', 'max:200'],
            'category_id' => ['required', 'exists:categories,id'],
            'sub_category_id' => ['required', 'exists:sub_categories,id'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'business_desc' => ['required', 'string', 'min:20', 'max:5000'],
            'phone' => ['required', 'digits:10'],
            'email' => ['required', 'email', 'max:191', Rule::unique('users', 'email')->ignore($this->user()->id)],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'company_name.required' => 'Business name is required.',
            'company_name.min' => 'Business name must be at least 4 characters.',
            'company_name.max' => 'Business name must not exceed 200 characters.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'Selected category is invalid.',
            'sub_category_id.required' => 'Please select a sub category.',
            'sub_category_id.exists' => 'Selected sub category is invalid.',
            'tagline.max' => 'Tagline must not exceed 255 characters.',
            'business_desc.required' => 'About business is required.',
            'business_desc.min' => 'About business must be at least 20 characters.',
            'business_desc.max' => 'About business must not exceed 5000 characters.',
            'phone.required' => 'Phone number is required.',
            'phone.digits' => 'Phone number must be exactly 10 digits.',
            'email.required' => 'Email is required.',
            'email.email' => 'Enter a valid email address.',
            'email.max' => 'Email must not exceed 191 characters.',
            'email.unique' => 'This email is already registered.',
            'address.max' => 'Address must not exceed 500 characters.',
            'city.required' => 'City is required.',
            'city.max' => 'City must not exceed 100 characters.',
            'logo.image' => 'Logo must be JPG, PNG, WebP, or GIF.',
            'logo.mimes' => 'Logo must be JPG, PNG, WebP, or GIF.',
            'logo.max' => 'Logo must not be larger than 2 MB.',
        ];
    }

    public function attributes(): array
    {
        return [
            'company_name' => 'business name',
            'category_id' => 'category',
            'sub_category_id' => 'sub category',
            'tagline' => 'tagline',
            'business_desc' => 'about business',
            'phone' => 'phone number',
            'email' => 'email',
            'address' => 'address',
            'city' => 'city',
            'logo' => 'company logo',
        ];
    }
}
