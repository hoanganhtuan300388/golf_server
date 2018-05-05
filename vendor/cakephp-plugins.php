<?php
$baseDir = dirname(dirname(__FILE__));
return [
    'plugins' => [
        'Api' => $baseDir . '/plugins/Api/',
        'Bake' => $baseDir . '/vendor/cakephp/bake/',
        'DebugKit' => $baseDir . '/vendor/cakephp/debug_kit/',
        'Josegonzalez/Upload' => $baseDir . '/vendor/josegonzalez/cakephp-upload/',
        'Migrations' => $baseDir . '/vendor/cakephp/migrations/'
    ]
];