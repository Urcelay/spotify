<?php

return [
    'default' => 'default',
    'documentations' => [
        'default' => [
            'api' => [
                'title' => 'Music API',
                'description' => 'API para gestionar canciones en Laravel',
                'version' => '1.0.0',
                'termsOfService' => '',
                'contact' => [
                    'email' => 'davisanderson87@gmail.com',
                ],
                'license' => [
                    'name' => 'MIT',
                    'url' => 'https://opensource.org/licenses/MIT',
                ],
            ],
                'routes' => [
                    'api' => 'api/documentation', // Ruta para acceder a la documentación
                ],

                'paths' => [
                    'use_absolute_path' => env('L5_SWAGGER_USE_ABSOLUTE_PATH', true),
                    'annotations' => [
                        base_path('app/Models'),
                        base_path('app/Http/Controllers'),
                    ],
                ],
            ],
        ],

        'defaults' => [
            'routes' => [
                'docs' => 'docs',
                'oauth2_callback' => 'api/oauth2-callback',
                'middleware' => [
                    'api' => [],
                    'asset' => [],
                    'docs' => [],
                ],
            ],

            'paths' => [
                'docs' => storage_path('api-docs'),
                'views' => base_path('resources/views/vendor/ls-swagger'),
                'base' => env('LS_SWAGGER_BASE_PATH', null),
                'excludes' => [],
                'docs_json' => 'api-docs.json',
                'docs_yaml' => 'api-docs.yaml',
            ],

            'generate_always' => env('LS_SWAGGER_GENERATE_ALWAYS', false),
            'generate_yaml_copy' => env('LS_SWAGGER_GENERATE_YAML_COPY', false),
            'proxy' => env('LS_SWAGGER_PROXY', null),
            'operations_sort' => env('LS_SWAGGER_OPERATIONS_SORT', null),
            'additional_config_url' => env('LS_SWAGGER_ADDITIONAL_CONFIG_URL', null),
            'validator_url' => env('LS_SWAGGER_VALIDATOR_URL', null),

            'constants' => [
                'LS_SWAGGER_CONST_HOST' => env('LS_SWAGGER_CONST_HOST', 'http://localhost:8000'),
            ],

            'securityDefinitions' => [
                'securitySchemes' => [],
                'security' => [],
            ],
        ],
    ];
