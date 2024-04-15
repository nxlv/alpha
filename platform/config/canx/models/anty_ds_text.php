<?php

return [
    /**
     *
     * Text
     * file: anty_ds_text-[x].[y].xml
     *
     */
    'data' => [
        'node' => 'text',
        'filename' => 'anty_ds_text-1.1.xml',
        'table_name' => 'notices',
    ],
    'fields' => [
        'text_id' => [ 'type' => 'attribute', 'target' => '_self', 'key' => 'text_id' ],
        'notice' => [ 'type' => 'node', 'node' => '_self' ]
    ]
];
