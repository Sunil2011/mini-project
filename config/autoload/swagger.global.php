<?php

return array(
    'swagger' => array(
        /**
         * List a path or paths containing Swagger Annotated classes
         */
        'paths' => array(
            __DIR__ . '/../../module/Api/src/Api/Controller',
        ),

        'resource_options' => array(
            'output' => 'array',
            'json_pretty_print' => true, // for outputtype 'json'
            'defaultApiVersion' => null,
            'defaultBasePath' => '/samples/public', // e.g. /api
            'defaultHost' => null, // example.com
            'schemes' => null, // e.g. ['http', 'https'],
        ),
    )
);
