<?php

namespace App\Http\Requests\Front;

use App\Support\SafeText;
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
            'social_website' => $this->filled('social_website') ? trim((string) $this->input('social_website')) : null,
            'social_subwebsite' => $this->filled('social_subwebsite') ? trim((string) $this->input('social_subwebsite')) : null,
            'social_facebook' => $this->filled('social_facebook') ? trim((string) $this->input('social_facebook')) : null,
            'social_twitter' => $this->filled('social_twitter') ? trim((string) $this->input('social_twitter')) : null,
            'social_linkedin' => $this->filled('social_linkedin') ? trim((string) $this->input('social_linkedin')) : null,
        ]);

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $hours = $this->input('business_hours', []);
        $normalized = [];

        foreach ($days as $day) {
            $dayData = $hours[$day] ?? [];
            $normalized[$day] = [
                'is_open' => (bool) ($dayData['is_open'] ?? false),
                'open' => $dayData['open'] ?? '10:00 AM',
                'close' => $dayData['close'] ?? '07:00 PM',
            ];
        }

        $this->merge(['business_hours' => $normalized]);
    }

    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'min:4', 'max:200', SafeText::titleRule()],
            'category_id' => ['required', 'exists:categories,id'],
            'sub_category_id' => ['required', 'array', 'min:1'],
            'sub_category_id.*' => [
                'integer',
                Rule::exists('sub_categories', 'id')->where(function ($query) {
                    $query->where('category_id', $this->input('category_id'))
                        ->where('status', 1);
                }),
            ],
            'tagline' => ['nullable', 'string', 'max:255', SafeText::titleRule()],
            'business_desc' => ['required', 'string', 'min:20', 'max:5000'],
            'phone' => ['required', 'digits:10'],
            'email' => ['required', 'email', 'max:191', Rule::unique('users', 'email')->ignore($this->user()->id)],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100', SafeText::titleRule()],
            'social_website' => ['nullable', 'url', 'max:255'],
            'social_subwebsite' => ['nullable', 'url', 'max:255'],
            'social_facebook' => ['nullable', 'url', 'max:255'],
            'social_twitter' => ['nullable', 'url', 'max:255'],
            'social_linkedin' => ['nullable', 'url', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
            'business_hours' => ['required', 'array'],
            'business_hours.*' => ['required', 'array'],
            'business_hours.*.is_open' => ['required', 'boolean'],
            'business_hours.*.open' => ['required', 'string'],
            'business_hours.*.close' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'company_name.required' => 'Business name is required.',
            'company_name.min' => 'Business name must be at least 4 characters.',
            'company_name.max' => 'Business name must not exceed 200 characters.',
            'company_name.regex' => SafeText::titleMessage('Business name'),
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'Selected category is invalid.',
            'sub_category_id.required' => 'Please select at least one sub category.',
            'sub_category_id.min' => 'Please select at least one sub category.',
            'sub_category_id.*.exists' => 'Selected sub category does not belong to the chosen category.',
            'tagline.max' => 'Tagline must not exceed 255 characters.',
            'tagline.regex' => SafeText::titleMessage('Tagline'),
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
            'city.regex' => SafeText::titleMessage('City'),
            'social_website.url' => 'Enter a valid website URL.',
            'social_subwebsite.url' => 'Enter a valid sub website URL.',
            'social_facebook.url' => 'Enter a valid Facebook URL.',
            'social_twitter.url' => 'Enter a valid Twitter / X URL.',
            'social_linkedin.url' => 'Enter a valid LinkedIn URL.',
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
            'sub_category_id.*' => 'sub category',
            'tagline' => 'tagline',
            'business_desc' => 'about business',
            'phone' => 'phone number',
            'email' => 'email',
            'address' => 'address',
            'city' => 'city',
            'social_website' => 'website',
            'social_subwebsite' => 'sub website',
            'social_facebook' => 'Facebook',
            'social_twitter' => 'Twitter / X',
            'social_linkedin' => 'LinkedIn',
            'logo' => 'company logo',
        ];
    }
}
