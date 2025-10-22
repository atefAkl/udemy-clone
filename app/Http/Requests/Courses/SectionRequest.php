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
            'title'         => 'required|string|max:255',
            'sort_order'    => 'nullable|int|min:1',
            'description'   => 'nullable|string|max:255|min:20',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required'         => __('validation.custom.title.required'),
            'title.string'           => __('validation.custom.title.string'),
            'title.max'              => __('validation.custom.title.max'),
            'description.string'     => __('validation.custom.description.string'),
            'description.max'        => __('validation.custom.description.max'),
            'description.min'        => __('validation.custom.description.min'),
            'sort_order.int'         => __('validation.custom.sort_order.int'),
            'sort_order.min'         => __('validation.custom.sort_order.min'),
        ];
    }
}
