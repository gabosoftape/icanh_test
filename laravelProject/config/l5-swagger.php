<?php

return [
    'api' => [
        /*
        |--------------------------------------------------------------------------
        | Edit to set the api's title
        |--------------------------------------------------------------------------
        */
        'title' => 'API Gestión de Vehículos',
    ],

    'routes' => [
        /*
        |--------------------------------------------------------------------------
        | Route for accessing api documentation interface
        |--------------------------------------------------------------------------
        */
        'api' => 'api/documentation',

        /*
        |--------------------------------------------------------------------------
        | Route for accessing parsed swagger annotations.
        |--------------------------------------------------------------------------
        */
        'docs' => 'docs',

        /*
        |--------------------------------------------------------------------------
        | Middleware allows to prevent unexpected access to API documentation
        |--------------------------------------------------------------------------
        */
        'middleware' => [
            'api' => [],
            'asset' => [],
            'docs' => [],
            'oauth2_callback' => [],
        ],
    ],

    'paths' => [
        /*
        |--------------------------------------------------------------------------
        | Absolute path to location where parsed swagger annotations will be stored
        |--------------------------------------------------------------------------
        */
        'docs' => storage_path('api-docs'),

        /*
        |--------------------------------------------------------------------------
        | File name of the generated json documentation file
        |--------------------------------------------------------------------------
        */
        'docs_json' => 'api-docs.json',

        /*
        |--------------------------------------------------------------------------
        | File name of the generated yaml documentation file
        |--------------------------------------------------------------------------
        */
        'docs_yaml' => 'api-docs.yaml',

        /*
        |--------------------------------------------------------------------------
        | Absolute paths to directory containing the swagger annotations are stored.
        |--------------------------------------------------------------------------
        */
        'annotations' => [
            base_path('app'),
        ],

        /*
        |--------------------------------------------------------------------------
        | Absolute path to directory where to export views
        |--------------------------------------------------------------------------
        */
        'views' => resource_path('views/vendor/l5-swagger'),

        /*
        |--------------------------------------------------------------------------
        | Edit to set the api's base path
        |--------------------------------------------------------------------------
        */
        'base' => env('L5_SWAGGER_BASE_PATH', null),

        /*
        |--------------------------------------------------------------------------
        | Edit to set path where swagger ui assets should be stored
        |--------------------------------------------------------------------------
        */
        'swagger_ui_assets_path' => env('L5_SWAGGER_UI_ASSETS_PATH', 'vendor/swagger-api/swagger-ui/dist/'),

        /*
        |--------------------------------------------------------------------------
        | Absolute path to directories that should be excluded from scanning
        |--------------------------------------------------------------------------
        */
        'excludes' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | API security definitions. Will be generated into documentation file.
    |--------------------------------------------------------------------------
    */
    'securityDefinitions' => [
        'securitySchemes' => [
            /*
            |--------------------------------------------------------------------------
            | Examples of Security definitions
            |--------------------------------------------------------------------------
            */
            /*
            'api_key_security_example' => [ // Unique name of security
                'type' => 'apiKey', // The type of the security scheme. Valid values are "basic", "apiKey" or "oauth2".
                'description' => 'A short description for security scheme',
                'name' => 'api_key', // The name of the header or query parameter to be used.
                'in' => 'header', // The location of the API key. Valid values are "query" or "header".
            ],
            'oauth2_security_example' => [
                'type' => 'oauth2',
                'description' => 'A short description for oauth2 security scheme.',
                'flow' => 'implicit', // The flow used by the OAuth2 security scheme. Valid values are "implicit", "password", "application" or "accessCode".
                'authorizationUrl' => 'http://example.com/auth', // The authorization URL to be used for (implicit/accessCode)
                //'tokenUrl' => 'http://example.com/auth' // The authorization URL to be used for (password/application/accessCode)
                'scopes' => [
                    'read:projects' => 'read your projects',
                    'write:projects' => 'modify projects in your account',
                ]
            ],
            */

            /* Open API 3.0 support
            'passport' => [ // Unique name of security
                'type' => 'oauth2', // The type of the security scheme. Valid values are "basic", "apiKey" or "oauth2".
                'description' => 'Laravel passport oauth2 security.',
                'in' => 'header',
                'scheme' => 'https',
                'flows' => [
                    "password" => [
                        "authorizationUrl" => config('app.url') . '/oauth/authorize',
                        "tokenUrl" => config('app.url') . '/oauth/token',
                        "refreshUrl" => config('app.url') . '/oauth/token/refresh',
                        "scopes" => []
                    ],
                ],
            ],
            */
        ],
        'security' => [
            /*
            |--------------------------------------------------------------------------
            | Examples of Securities
            |--------------------------------------------------------------------------
            */
            /*
            [
                'api_key_security_example' => [],
                'oauth2_security_example' => ['read:projects', 'write:projects'],
            ],
            */

            /* Open API 3.0 support
            [
                'passport' => [],
            ],
            */
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Set this to `true` in development mode so that docs would be regenerated on each request
    |--------------------------------------------------------------------------
    */
    'generate_always' => env('L5_SWAGGER_GENERATE_ALWAYS', false),

    /*
    |--------------------------------------------------------------------------
    | Set this to `true` to generate a copy of documentation in yaml format
    |--------------------------------------------------------------------------
    */
    'generate_yaml_copy' => env('L5_SWAGGER_GENERATE_YAML_COPY', false),

    /*
    |--------------------------------------------------------------------------
    | Edit to set the swagger version number
    |--------------------------------------------------------------------------
    */
    'swagger_version' => env('SWAGGER_VERSION', '3.0'),

    /*
    |--------------------------------------------------------------------------
    | Edit to trust the proxy's ip address - needed for AWS Load Balancer
    |--------------------------------------------------------------------------
    */
    'proxy' => false,

    /*
    |--------------------------------------------------------------------------
    | Configs plugin allows to fetch external configs instead of passing them to SwaggerUIBundle.
    | See https://github.com/swagger-api/swagger-ui#configs-plugin for more information.
    |--------------------------------------------------------------------------
    */
    'additional_config_url' => null,

    /*
    |--------------------------------------------------------------------------
    | Apply a sort to the operation list of each API. It can be 'alpha' (sort by paths alphanumerically),
    | 'method' (sort by HTTP method).
    | Default is the order returned by the server unchanged.
    |--------------------------------------------------------------------------
    */
    'operations_sort' => env('L5_SWAGGER_OPERATIONS_SORT', null),

    /*
    |--------------------------------------------------------------------------
    | Uncomment to pass the validatorUrl parameter to SwaggerUi init on the JS
    | side.  A null value here disables validation.
    |--------------------------------------------------------------------------
    */
    'validator_url' => null,

    /*
    |--------------------------------------------------------------------------
    | Uncomment to add constants which can be used in annotations
    |--------------------------------------------------------------------------
    */
    'constants' => [
        'L5_SWAGGER_CONST_HOST' => env('L5_SWAGGER_CONST_HOST', 'http://localhost:9000'),
    ],
];


