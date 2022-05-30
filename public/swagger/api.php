<?php

require dirname(__DIR__) . '/../vendor/autoload.php';
$openapi = \OpenApi\Generator::scan([dirname(__DIR__) . '/../App/Controllers/Api.php']);
header('Content-Type: application/json');
echo $openapi->toJson();
