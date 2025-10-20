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
            'image' => 'يجب أن يكون البوستر صورة صالحة.',
            'mimes' => 'يجب أن يكون البوستر من النوع: png, jpg, jpeg, webp.',
            'max' => 'يجب ألا يتجاوز حجم البوستر 3 ميجابايت.',
            'aspect_ratio' => 'يجب أن تكون أبعاد البوستر بنسبة 16:9 (بسماحية 10%).',
        ],
        'promo_video' => [
            'file' => 'يجب أن يكون الفيديو الترويجي ملفاً صالحاً.',
            'mimes' => 'يجب أن يكون الفيديو الترويجي من النوع: avi, flv, webm, wmv, mp4.',
            'max' => 'يجب ألا يتجاوز حجم الفيديو الترويجي 10 ميجابايت.',
            'duration' => 'يجب أن تكون مدة الفيديو بين دقيقة واحدة و10 دقائق.',
            'aspect_ratio' => 'يجب أن تكون أبعاد الفيديو بنسبة 16:9 (بسماحية 10%).',
            'quality' => 'يجب أن تكون جودة الفيديو HD على الأقل (720p).',
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
        'banner' => 'البوستر',
        'promo_video' => 'الفيديو الترويجي',
        'banner_url' => 'رابط البوستر',
        'video_url' => 'رابط الفيديو',
    ],
];
