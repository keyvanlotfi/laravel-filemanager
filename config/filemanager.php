<?php

return [
    'disk' => 'public',
    'prefix' => 'filemanager',
    'allowed_file_types' => ['jpg', 'JPG', 'jpeg', 'png', 'gif', 'pdf', 'webp'],
    'max_upload_size' => 20, // in Mb
    'middlewares' => [
//        'auth',
    ]
];
