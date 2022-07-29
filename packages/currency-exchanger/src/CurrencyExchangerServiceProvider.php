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

    }
}
