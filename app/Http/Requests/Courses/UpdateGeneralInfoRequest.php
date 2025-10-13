<?php

namespace App\Http\Requests\Courses;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGeneralInfoRequest extends FormRequest
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
            'title'                 => ['required', 'string', 'between:4,60'],
            'subtitle'              => ['required', 'string', 'between:12,160'],
            'short_description'     => ['required', 'string', 'between:45,1024'],
            'description'           => ['required', 'string', 'between:160,5000'],
            'category_id'              => ['required', 'exists:categories,id'],
            'language'              => ['required', 'string', 'max:3'],
            'level'                 => ['required', Rule::in(['beginner', 'intermediate', 'professional', 'advanced'])],
            'price'                 => ['nullable', 'numeric', 'min:0'],
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function messages()
    {
        return [
            'title.required'                 => __('courses.title_is_required'),
            'title.between'                  => __('courses.title_max_100_chars'),
            'subtitle.required'              => __('courses.subtitle_is_required'),
            'subtitle.between'               => __('courses.subtitle_max_32_chars'),
            'short_description.required'     => __('courses.short_description_is_required'),
            'short_description.between'      => __('courses.short_description_between'),
            'description.required'           => __('courses.description_is_required'),
            'description.between'            => __('courses.description_between'),
            'category_id.required'           => __('courses.category_is_required'),
            'language.required'              => __('courses.language_is_required'),
            'language.max'                   => __('courses.language_max_3_chars'),
            'level.required'                 => __('courses.level_is_required'),
            'price.required'                 => __('courses.price_is_required'),
            'price.min'                      => __('courses.price_min_0'),
        ];
    }
}
