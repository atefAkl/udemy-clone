<?php

namespace App\Http\Requests\Courses;

use Illuminate\Foundation\Http\FormRequest;

class VideoLessonRequest extends FormRequest
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
            'description'       => 'nullable|string|between:4,255',
            'video_source'      => 'required|string|in:upload,url',
            'video_url'         => 'required_if:video_source,url|nullable|url',
            'video_file'        => 'required_if:video_source,upload|nullable|file|mimes:mp4,avi,wmv,flv,mpg,mpeg,mov,webm|max:102400',
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
            'video_source.required'     => __('validation.custom.video_source.required'),
            'video_source.in'           => __('validation.custom.video_source.in'),
            'video_url.required_if'     => __('validation.custom.video_url.required_if'),
            'video_url.url'             => __('validation.custom.video_url.url'),
            'video_file.required_if'    => __('validation.custom.video_file.required_if'),
            'video_file.file'           => __('validation.custom.video_file.file'),
            'video_file.mimes'          => __('validation.custom.video_file.mimes'),
            'video_file.max'            => __('validation.custom.video_file.max'),
        ];
    }
}
