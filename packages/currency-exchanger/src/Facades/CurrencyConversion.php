<?php

namespace Shakilahmmed\CurrencyExchanger\Facades;

use Illuminate\Support\Facades\Facade;

class CurrencyConversion extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return 'currency-exchanger';
    }
}
