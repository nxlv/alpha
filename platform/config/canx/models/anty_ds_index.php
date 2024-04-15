<?php

return [
    /**
     *
     * Indexes
     * file: anty_ds_index-[x].[y].xml
     *
     */
    'data' => [
        'node' => 'index',
        'filename' => 'anty_ds_index-1.1.xml',
        'table_name' => 'indexes'
    ],
    'fields' => [
        'index_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'index_id' ],
        'index_name' => [ 'type' => 'node', 'key' => 'index_name' ],
        'universal_ticker' => [ 'type' => 'node', 'key' => 'universal_ticker' ],
        'live_date' => [ 'type' => 'node', 'key' => 'live_date', 'data_type' => 'date' ],
        'oldest_date' => [ 'type' => 'node', 'key' => 'oldest_date', 'data_type' => 'date' ],
        'most_recent_date' => [ 'type' => 'node', 'key' => 'most_recent_date', 'data_type' => 'date' ],
    ]
];
