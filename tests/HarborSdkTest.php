<?php
require_once __DIR__.'/../vendor/autoload.php';

$sdk = new Harbor\Sdk(
    [
        'domain' => 'http://harbor.test', // Store domain
        'api_key' => 'api key', // api key
        'password' => 'password', // password
        'retry' => 5, // Number of retries when the request failed
    ]
);
$res = $sdk->product->get(123456);
var_dump($res);