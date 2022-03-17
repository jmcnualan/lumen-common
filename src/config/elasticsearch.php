<?php

return [
    'connections' => [
        'table1' => [
            'hosts' => env('ES_TABLE1_HOSTS', 'http://localhost:9200'),
            'index' => env('ES_TABLE1_INDEX', 'table1'),
        ],
    ],
];
