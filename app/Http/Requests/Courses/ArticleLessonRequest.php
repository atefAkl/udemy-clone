<?php

namespace App\Http\Requests\Courses;

use Illuminate\Foundation\Http\FormRequest;

class ArticleLessonRequest extends FormRequest
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
            'article_body'      => 'required|string|min:50',
            'poster_source'     => 'required|string|in:upload,url',
            'poster_url'        => 'required_if:poster_source,url|nullable|url',
            'poster_file'       => 'required_if:poster_source,upload|nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
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
            'article_body.required'     => __('validation.custom.article_body.required'),
            'article_body.min'          => __('validation.custom.article_body.min'),
            'poster_source.required'    => __('validation.custom.poster_source.required'),
            'poster_source.in'          => __('validation.custom.poster_source.in'),
            'poster_url.required_if'    => __('validation.custom.poster_url.required_if'),
            'poster_url.url'            => __('validation.custom.poster_url.url'),
            'poster_file.required_if'   => __('validation.custom.poster_file.required_if'),
            'poster_file.image'         => __('validation.custom.poster_file.image'),
            'poster_file.mimes'         => __('validation.custom.poster_file.mimes'),
            'poster_file.max'           => __('validation.custom.poster_file.max'),
        ];
    }
}
