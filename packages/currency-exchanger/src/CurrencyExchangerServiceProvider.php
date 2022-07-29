<?php

namespace Shakilahmmed\CurrencyExchanger;

use Illuminate\Support\ServiceProvider;

class CurrencyExchangerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('currency-exchanger', function ($app) {
            return new CurrencyExchanger();
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/currency-exchanger.php' => config_path('currency-exchanger.php'),
        ], 'config');
    }
}
