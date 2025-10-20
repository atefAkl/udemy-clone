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
    ],
];
