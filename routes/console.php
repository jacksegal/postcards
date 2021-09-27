<?php

use ClickSend\Api\PostPostcardApi;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('clicksend:return', function () {

    // Configure HTTP basic authorization: BasicAuth
    $config = ClickSend\Configuration::getDefaultConfiguration()
        ->setUsername(env('CLICKSEND_USERNAME'))
        ->setPassword(env('CLICKSEND_API_KEY'));

    $apiInstance = new ClickSend\Api\PostReturnAddressApi(new GuzzleHttp\Client(),$config);
    $page = 1; // int | Page number
    $limit = 10; // int | Number of records per page

    try {
        $result = $apiInstance->postReturnAddressesGet($page, $limit);
        $this->info($result);
    } catch (Exception $e) {
        echo 'Exception when calling PostReturnAddressApi->postReturnAddressesGet: ', $e->getMessage(), PHP_EOL;
    }

})->purpose('Get all Return Addresses');
