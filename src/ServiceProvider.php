<?php

namespace hakkahio\AzureSocialite;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/azure-oath.php' => config_path('azure-oath.php'),
        ]);

        $this->mergeConfigFrom(
            __DIR__.'/config/azure-oath.php', 'azure-oath'
        );

        $this->app['Laravel\Socialite\Contracts\Factory']->extend('azure-oauth', function($app){
            return $app['Laravel\Socialite\Contracts\Factory']->buildProvider(
                'hakkahio\AzureSocialite\AzureOauthProvider',
                config('azure-oath.credentials')
            );
        });

        $this->app['router']->group(['middleware' => config('azure-oath.routes.middleware')], function($router){
            $router->get(config('azure-oath.routes.login'), 'hakkahio\AzureSocialite\AuthController@redirectToOauthProvider');
            $router->get(config('azure-oath.routes.callback'), 'hakkahio\AzureSocialite\AuthController@handleOauthResponse');
        });
    }
}
