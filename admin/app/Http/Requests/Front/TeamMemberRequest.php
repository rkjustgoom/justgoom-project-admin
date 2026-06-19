<?php

namespace App\Http\Requests\Front;

use Illuminate\Foundation\Http\FormRequest;

class TeamMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation(): void
    {
        $departmentSelect = $this->input('department_select');
        $department = $departmentSelect === '__other__'
            ? trim((string) $this->input('department_other'))
            : trim((string) ($departmentSelect ?? ''));

        $this->merge([
            'is_primary' => $this->boolean('is_primary'),
            'status' => $this->input('status', 1),
            'department' => $department !== '' ? $department : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'designation' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:191'],
            'phone' => ['required', 'string', 'regex:/^\d{10}$/'],
            'department_select' => ['nullable', 'string'],
            'department_other' => ['nullable', 'required_if:department_select,__other__', 'string', 'max:100'],
            'department' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'in:0,1'],
            'short_info' => ['nullable', 'string', 'max:5000'],
            'is_primary' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Phone number must be exactly 10 digits.',
            'department_other.required_if' => 'Please enter a department name when Other is selected.',
            'image.max' => 'Profile photo must not be larger than 2 MB.',
        ];
    }
}
