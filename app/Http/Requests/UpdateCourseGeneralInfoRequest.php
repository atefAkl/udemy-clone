<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourseGeneralInfoRequest extends FormRequest
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
            'subtitle'              => ['required', 'string', 'between:4,32'],
            'short_description'     => ['required', 'string', 'between:45,1024'],
            'description'           => ['required', 'string', 'between:160,1024'],
            'category'              => ['required', 'exists:categories,id'],
            'language'              => ['required', 'string', 'max:3'],
            'level'                 => ['required', Rule::in(['beginner', 'intermediate', 'professional', 'advanced'])],
            'price'                 => ['required', 'numeric', 'min:0'],
            'banner'                => ['nullable', 'file', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'banner_source'         => ['nullable', 'string', 'in:upload,link'],

            'promo_video'           => ['nullable', 'file', 'mimes:mp4,mov,avi,mkv', 'max:40960'],
            'video_source'          => ['nullable', 'string', 'in:upload,link'],

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
            'short_description.between'      => __('courses.short_description_max_1024_chars'),
            'description.required'           => __('courses.description_is_required'),
            'description.between'            => __('courses.description_max_1024_chars'),
            'category.required'              => __('courses.category_is_required'),
            'language.required'              => __('courses.language_is_required'),
            'language.max'                   => __('courses.language_max_3_chars'),
            'level.required'                 => __('courses.level_is_required'),
            'price.required'                 => __('courses.price_is_required'),
            'price.min'                      => __('courses.price_min_0'),
            'banner.required_if'             => __('courses.banner_is_required_if'),
            'banner.mimes'                   => __('courses.banner_mimes'),
            'banner.max'                     => __('courses.banner_max_2mb'),
            'banner_source.required'         => __('courses.banner_source_is_required'),
            'banner_source.in'               => __('courses.banner_source_in'),
            'banner_url.required_if'         => __('courses.banner_url_is_required_if'),
            'banner_url.url'                 => __('courses.banner_url_url'),
            'promo_video.required_if'        => __('courses.promo_video_is_required_if'),
            'promo_video.mimes'              => __('courses.promo_video_mimes'),
            'promo_video.file'               => __('courses.promo_video_failed_to_upload'),
            'promo_video.max'                => __('courses.promo_video_max_40mb'),
            'video_source.required'          => __('courses.video_source_is_required'),
            'video_source.in'                => __('courses.video_source_in'),
            'video_url.required_if'          => __('courses.video_url_is_required_if'),
            'video_url.url'                  => __('courses.video_url_url'),
        ];
    }
}
