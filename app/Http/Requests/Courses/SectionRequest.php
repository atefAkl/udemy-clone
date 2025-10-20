<?php

namespace App\Http\Requests\Courses;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Log;

class SectionRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'banner' => [
                'nullable',
                'image',
                'mimes:png,jpg,jpeg,webp',
                'max:3072', // 3 MB in KB
            ],
            'promo_video' => [
                'nullable',
                'file',
                'mimes:avi,flv,webm,wmv,mp4',
                'max:10240', // 10 MB in KB
            ],
            'banner_url' => 'nullable|url',
            'video_url' => 'nullable|url',
            'banner_source' => 'nullable|string|in:device,url,library,clipboard',
            'video_source' => 'nullable|string|in:device,url,library',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            // التحقق من الصورة
            if ($this->hasFile('banner')) {
                $this->validateImageDimensions($validator);
            }
        });
    }

    /**
     * التحقق من أبعاد الصورة (16:9 بسماحية 10%)
     */
    protected function validateImageDimensions($validator): void
    {
        $image = $this->file('banner');
        $dimensions = @getimagesize($image->getRealPath());

        if ($dimensions) {
            $width = $dimensions[0];
            $height = $dimensions[1];
            $aspectRatio = $width / $height;

            // نسبة 16:9 = 1.778
            $targetRatio = 16 / 9;
            $tolerance = 0.1; // 10% سماحية

            $minRatio = $targetRatio * (1 - $tolerance);
            $maxRatio = $targetRatio * (1 + $tolerance);

            if ($aspectRatio < $minRatio || $aspectRatio > $maxRatio) {
                $validator->errors()->add(
                    'banner',
                    __('validation.custom.banner.aspect_ratio')
                );
            }
        }
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'banner' => __('validation.attributes.banner'),
            'promo_video' => __('validation.attributes.promo_video'),
            'banner_url' => __('validation.attributes.banner_url'),
            'video_url' => __('validation.attributes.video_url'),
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'banner.image' => __('validation.custom.banner.image'),
            'banner.mimes' => __('validation.custom.banner.mimes'),
            'banner.max' => __('validation.custom.banner.max'),
            'promo_video.file' => __('validation.custom.promo_video.file'),
            'promo_video.mimes' => __('validation.custom.promo_video.mimes'),
            'promo_video.max' => __('validation.custom.promo_video.max'),
        ];
    }
}
