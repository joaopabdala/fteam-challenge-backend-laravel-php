<?php

return [
    'enable' => env('HTTP_LOGGER_ENABLE', true),

    'log_channel' => env('HTTP_LOGGER_LOG_CHANNEL', 'stderr'),

    'log_level' => env('HTTP_LOGGER_LOG_LEVEL', 'info'),

    'delete_json_logs' => env('DELETE_JSON_LOGS', true),

    'hidden_headers' => [
        'cookie',
        'x-csrf-token',
        'x-xsrf-token',
        'authorization',
        'access_token',
        'set-cookie',
    ],

    'hidden_parameters' => [
        '_token',
        'password',
        'password_confirmation',
        'api_id',
        'api_key',
        'access_token',
        'senha',
        'token',
        'qr_code',
        'qr_code_url',
    ],

    'sanitizer_nested_parameters' => [
    ],

    'only_paths' => [
        //
    ],

    'except_paths' => array_filter([
        '/telescope*',
        '/horizon*',
        '/metrics',
        '/health',
        ...explode(',', env('HTTP_LOGGER_EXCEPT_PATHS', '')),
    ]),
];
