<?php
require_once __DIR__.'/../vendor/autoload.php';

$sdk = new Harbor\Sdk(
    [
        'domain' => 'http://harbor.test', // Store domain
        'api_key' => 'bf0d31e46120e9dc2758f9bbf2f0e304', // api key
        'password' => 'e6533ccdc497e7711fbfc2e4a3e735a9', // password
        'retry' => 5, // Number of retries when the request failed
    ]
);
$res = $sdk->webhook->list();
var_dump($res);