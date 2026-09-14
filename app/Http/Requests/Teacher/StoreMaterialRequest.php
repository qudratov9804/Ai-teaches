<?php

namespace App\Http\Requests\Teacher;

use App\Models\Material;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class StoreMaterialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', [Material::class, $this->route('subject')]);
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
            'author' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'type' => ['required', Rule::in(array_keys(Material::types()))],
            'topic_id' => [
                'nullable',
                Rule::exists('topics', 'id')->where('subject_id', $this->route('subject')->id),
            ],
            'published_year' => ['nullable', 'integer', 'min:1900', 'max:'.(date('Y') + 1)],
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
            'title.required' => 'Material nomi kiritilishi shart.',
            'type.required' => 'Material turini tanlang.',
            'type.in' => "Noto'g'ri material turi tanlandi.",
            'topic_id.exists' => 'Tanlangan mavzu ushbu fanga tegishli emas.',
            'file.required' => 'Fayl yuklash majburiy.',
            'file.max' => 'Fayl hajmi 50 MB dan oshmasligi kerak.',
            'file.mimes' => 'Ruxsat etilgan fayl turlari: PDF, DOC, DOCX, PPT, PPTX, TXT, XLS, XLSX.',
        ];
    }
}
