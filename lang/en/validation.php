<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "rule.attribute" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'banner' => [
            'image' => 'The banner must be a valid image.',
            'mimes' => 'The banner must be a file of type: png, jpg, jpeg, webp.',
            'max' => 'The banner size must not exceed 3 megabytes.',
            'aspect_ratio' => 'The banner dimensions must be in 16:9 aspect ratio (with 10% tolerance).',
        ],
        'promo_video' => [
            'file' => 'The promo video must be a valid file.',
            'mimes' => 'The promo video must be a file of type: avi, flv, webm, wmv, mp4.',
            'max' => 'The promo video size must not exceed 10 megabytes.',
            'duration' => 'The video duration must be between 1 and 10 minutes.',
            'aspect_ratio' => 'The video dimensions must be in 16:9 aspect ratio (with 10% tolerance).',
            'quality' => 'The video quality must be at least HD (720p).',
        ],
        'sort_order' => [
            'int' => 'The order must be a valid number.',
            'min' => 'The order must be at least 1.',
        ],
        'section_id' => [
            'required' => 'Section ID is required.',
            'exists' => 'The selected section does not exist.',
        ],
        'title' => [
            'required' => 'Title is required.',
            'between' => 'Title must be between :min and :max characters.',
        ],
        'description' => [
            'between' => 'Description must be between :min and :max characters.',
        ],
        'video_source' => [
            'required' => 'Video source is required.',
            'in' => 'Video source must be either upload or URL.',
        ],
        'video_url' => [
            'required_if' => 'Video URL is required when video source is URL.',
            'url' => 'Video URL must be a valid URL.',
        ],
        'video_file' => [
            'required_if' => 'Video file is required when video source is upload.',
            'file' => 'Video must be a valid file.',
            'mimes' => 'Video must be a file of type: mp4, avi, wmv, flv, mpg, mpeg, mov, webm.',
            'max' => 'Video file size must not exceed 100MB.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'banner' => 'Banner',
        'promo_video' => 'Promo Video',
        'banner_url' => 'Banner URL',
        'video_url' => 'Video URL',
        'sort_order' => 'Order',
    ],
];
