<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('subject'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required', 'string', 'max:50',
                Rule::unique('subjects', 'code')->ignore($this->route('subject')),
            ],
            'description' => ['nullable', 'string', 'max:5000'],
            'course' => ['nullable', 'integer', 'min:1', 'max:6'],
            'semester' => ['nullable', 'integer', 'min:1', 'max:12'],
            'credit' => ['nullable', 'integer', 'min:1', 'max:30'],
            'is_open' => ['boolean'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Fan nomi kiritilishi shart.',
            'name.max' => 'Fan nomi 255 belgidan oshmasligi kerak.',
            'code.required' => 'Fan kodi kiritilishi shart.',
            'code.unique' => 'Bu fan kodi allaqachon band qilingan.',
            'course.integer' => 'Kurs raqam bo\'lishi kerak.',
            'course.max' => 'Kurs 1 dan 6 gacha bo\'lishi kerak.',
            'semester.integer' => 'Semestr raqam bo\'lishi kerak.',
            'credit.integer' => 'Kredit raqam bo\'lishi kerak.',
        ];
    }
}
