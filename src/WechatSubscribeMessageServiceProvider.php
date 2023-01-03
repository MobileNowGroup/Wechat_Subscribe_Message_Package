<?php

namespace MobileNowGroup\SubscribeMessage;

use Illuminate\Support\ServiceProvider;

class WechatSubscribeMessageServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../listeners/WechatSubscribeMessageListener.php.step' => app_path('Listeners/WechatSubscribeMessageListener.php')
            ], 'listener');
        }
    }
}