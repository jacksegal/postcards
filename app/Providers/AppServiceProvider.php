<?php

namespace App\Providers;

use ClickSend\Api\PostPostcardApi;
use ClickSend\Configuration;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PostPostcardApi::class, function($app) {
            $config = Configuration::getDefaultConfiguration()
                ->setUsername(getenv('CLICKSEND_USERNAME'))
                ->setPassword(getenv('CLICKSEND_API_KEY'));

            return new PostPostcardApi(app(Client::class), $config);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
