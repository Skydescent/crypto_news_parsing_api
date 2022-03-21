<?php

return [

    'default' => env('QUEUE_CONNECTION', 'sync'),

    'connections' => [
        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => '{default}',
            'retry_after' => 90,
        ]
    ]
];
