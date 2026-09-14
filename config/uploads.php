<?php

return [

    /*
    |--------------------------------------------------------------------------
    | O'quv materiallari uchun fayl yuklash sozlamalari
    |--------------------------------------------------------------------------
    |
    | Curriculum va Material fayllari uchun ruxsat etilgan kengaytmalar,
    | MIME turlari va maksimal fayl hajmi (kilobaytlarda, Laravel validation
    | "max" qoidasiga mos).
    |
    | Eslatma: PHP'ning upload_max_filesize va post_max_size sozlamalari
    | bu qiymatdan katta bo'lishi kerak, aks holda katta fayllar PHP
    | darajasida rad etiladi (README.md'ga qarang).
    |
    */

    'disk' => 'local',

    'max_size_kb' => 51200, // 50 MB

    'allowed_extensions' => ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'txt', 'xls', 'xlsx'],

    'allowed_mime_types' => [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'text/plain',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ],
];
