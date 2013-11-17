<?php

include __DIR__ . '/vendor/autoload.php';

use G\RestFull\Silex\RestFullApplication;

$app = new RestFullApplication([
    'debug' => true,
    'class.map.path' => __DIR__ . '/config/resourceClassMap.yml',
    'auto.injection.map.path' => __DIR__ . '/config/autoDependenciesClassMap.yml',
    'base.path' => 'rest' // default value
]);

$app->run();