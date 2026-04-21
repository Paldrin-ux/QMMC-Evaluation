<?php

return [
    'default' => env('DB_CONNECTION', 'sqlite'),

    'connections' => [
        'sqlite' => [
            'driver'                  => 'sqlite',
            'database'                => database_path('database.sqlite'),
            'prefix'                  => '',
            'foreign_key_constraints' => true,
        ],

        'mysql' => [
            'driver'         => 'mysql',
            'host'           => env('DB_HOST', '127.0.0.1'),
            'port'           => env('DB_PORT', '3306'),
            'database'       => env('DB_DATABASE', 'qmmc_evaluation'),
            'username'       => env('DB_USERNAME', 'root'),
            'password'       => env('DB_PASSWORD', ''),
            'charset'        => 'utf8mb4',
            'collation'      => 'utf8mb4_unicode_ci',
            'prefix'         => '',
            'prefix_indexes' => true,
            'strict'         => true,
            'engine'         => null,
        ],

        // Move it INSIDE the connections array here
        'intranet' => [
            'driver'    => 'mysql',
            'host'      => env('DB_INTRANET_HOST', '127.0.0.1'),
            'port'      => env('DB_INTRANET_PORT', '3306'),
            'database'  => env('DB_INTRANET_DATABASE'),
            'username'  => env('DB_INTRANET_USERNAME'),
            'password'  => env('DB_INTRANET_PASSWORD'),
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],
    ], // <--- This closing bracket must be AFTER 'intranet'

    'migrations' => 'migrations',
];