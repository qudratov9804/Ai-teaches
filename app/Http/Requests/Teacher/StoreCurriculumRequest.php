<?php

namespace App\Http\Requests\Teacher;

use App\Models\Curriculum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class StoreCurriculumRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', [Curriculum::class, $this->route('subject')]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'file' => [
                'required',
                File::types(config('uploads.allowed_extensions'))
                    ->max(config('uploads.max_size_kb')),
            ],
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
            'title.required' => 'Hujjat nomi kiritilishi shart.',
            'file.required' => 'Fayl yuklash majburiy.',
            'file.max' => 'Fayl hajmi 50 MB dan oshmasligi kerak.',
            'file.mimes' => 'Ruxsat etilgan fayl turlari: PDF, DOC, DOCX, PPT, PPTX, TXT, XLS, XLSX.',
        ];
    }
}
