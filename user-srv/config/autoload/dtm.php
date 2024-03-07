<?php

declare(strict_types=1);
/**
 * This file is part of DTM-PHP.
 *
 * @license  https://github.com/dtm-php/dtm-client/blob/master/LICENSE
 */
use DtmClient\Constants\Protocol;
use DtmClient\Constants\DbType;

return [
    'protocol' => Protocol::HTTP,
    'server' => env('DTM_HOST'),
    'port' => [
        'http' => 36789,
        'grpc' => 36790,
    ],
    'barrier' => [
        'db' => [
            'type' => DbType::MySQL
        ],
        'apply' => [],
    ],
    'guzzle' => [
        'options' => [],
    ],
];
