<?php

return [
    'show_warnings' => false,

    'public_path' => null,

    'convert_entities' => true,

    'options' => [
        'font_dir'               => storage_path('fonts/'),
        'font_cache'             => storage_path('fonts/cache/'),

        'temp_dir'               => sys_get_temp_dir(),
        'chroot'                 => realpath(base_path()),

        'default_font'           => 'Amiri',           // ← Important
        'default_paper_size'     => 'a4',
        'default_paper_orientation' => 'portrait',

        'isRemoteEnabled'        => true,
        'isHtml5ParserEnabled'   => true,
        'isFontSubsettingEnabled'=> true,

        'dpi'                    => 150,
        'enable_php'             => false,
        'enable_javascript'      => false,

        'font_height_ratio'      => 1.0,
    ],
];

