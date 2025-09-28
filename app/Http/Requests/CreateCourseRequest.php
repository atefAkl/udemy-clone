<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCourseRequest extends FormRequest
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
            'title'                 => 'required|string|between:10,45',
            'short_description'     => 'required|string|between:45,160',
            'description'           => 'required|string|between:50,500',
            'category_id'           => 'required|exists:categories,id',
            'language'              => 'required',
            'target_level'          => 'required|in:beginner,intermediate,advanced,expert',
            'price'                 => 'required|numeric|min:0',
            'launch_date'           => 'nullable|date|after_or_equal:today',
            'launch_time'           => 'nullable|string',
            'thumbnail'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // 5MB max
            'preview_video'         => 'nullable|file|mimes:mp4,mov,avi,wmv|max:102400', // 100MB max
            'requirements'          => 'nullable|string|max:1000',
            'objectives'            => 'nullable|string|max:1000',
            'has_certificate'       => 'boolean',
            'access_duration_type'  => 'required|in:unlimited,limited',
            'access_duration_value' => 'nullable|integer|min:1|required_if:access_duration_type,limited',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function messages(): array
    {
        return [
            'title.required'                    => 'course title is required',
            'title.string'                      => 'course title must be a string',
            'title.between'                     => 'course title must be between 10 and 45 characters',
            'short_description.required'        => 'course short description is required',
            'short_description.string'          => 'course short description must be a string',
            'short_description.between'         => 'course short description must be between 45 and 160 characters',
            'description.required'              => 'course description is required',
            'description.string'                => 'course description must be a string',
            'description.between'               => 'course description must be between 50 and 500 characters',
            'category_id.required'              => 'course category is required',
            'category_id.exists'                => 'course category does not exist',
            'language.required'                 => 'course language is required',
            'language.in'                       => 'course language must be ar or en',
            'target_level.required'             => 'course target level is required',
            'target_level.in'                   => 'course target level must be beginner, intermediate, advanced or professional',
            'price.required'                    => 'course price is required',
            'price.numeric'                     => 'course price must be a number',
            'price.min'                         => 'course price must be at least 0',
            'launch_date.required'              => 'course launch date is required',
            'launch_date.date'                  => 'course launch date must be a date',
            'launch_date.after_or_equal'        => 'course launch date must be after or equal to today',
            'launch_time.string'                => 'course launch time must be a string',
            'thumbnail.required'                => 'course thumbnail is required',
            'thumbnail.image'                   => 'course thumbnail must be an image',
            'thumbnail.mimes'                   => 'course thumbnail must be a jpeg, png, jpg or webp image',
            'thumbnail.max'                     => 'course thumbnail must be less than 5MB',
            'preview_video.required'            => 'course preview video is required',
            'preview_video.file'                => 'course preview video must be a file',
            'preview_video.mimes'               => 'course preview video must be a mp4, mov, avi or wmv video',
            'preview_video.max'                 => 'course preview video must be less than 100MB',
            'requirements.string'               => 'course requirements must be a string',
            'requirements.max'                  => 'course requirements must be less than 1000 characters',
            'objectives.string'                 => 'course objectives must be a string',
            'objectives.max'                    => 'course objectives must be less than 1000 characters',
            'has_certificate.boolean'           => 'course has certificate must be a boolean',
            'access_duration_type.in'           => 'course access duration type must be unlimited or limited',
            'access_duration_value.integer'     => 'course access duration value must be an integer',
            'access_duration_value.required_if' => 'course access duration value is required if access duration type is limited',
        ];
    }
}
