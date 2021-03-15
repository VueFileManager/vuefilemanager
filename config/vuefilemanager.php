<?php

return [

    'version'    => '2.0',

    // Define size of chunk uploaded by MB. E.g. integer 128 means chunk size will be 128MB.
    'chunk_size' => env('CHUNK_SIZE', '128'),
];
