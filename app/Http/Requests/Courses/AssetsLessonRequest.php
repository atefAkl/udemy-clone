<?php

namespace App\Http\Requests\Courses;

use Illuminate\Foundation\Http\FormRequest;

class AssetsLessonRequest extends FormRequest
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
            'section_id'        => 'required|exists:sections,id',
            'title'             => 'required|string|between:4,100',
            'description'       => 'nullable|string|between:4,500',
            'assets_files'      => 'required|array|min:1|max:10',
            'assets_files.*'    => 'required|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar,txt,csv|max:10240', // 10MB per file
        ];
    }

    public function messages(): array
    {
        return [
            'section_id.required'       => __('validation.custom.section_id.required'),
            'section_id.exists'         => __('validation.custom.section_id.exists'),
            'title.required'            => __('validation.custom.title.required'),
            'title.between'             => __('validation.custom.title.between'),
            'description.between'       => __('validation.custom.description.between'),
            'assets_files.required'     => 'Please upload at least one file.',
            'assets_files.array'        => 'Invalid files format.',
            'assets_files.min'          => 'Please upload at least one file.',
            'assets_files.max'          => 'You can upload a maximum of 10 files.',
            'assets_files.*.required'   => 'Each file is required.',
            'assets_files.*.file'       => 'Each upload must be a valid file.',
            'assets_files.*.mimes'      => 'Only PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, ZIP, RAR, TXT, CSV files are allowed.',
            'assets_files.*.max'        => 'Each file must not exceed 10MB.',
        ];
    }
}
