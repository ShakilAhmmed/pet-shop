<?php

namespace Shakilahmmed\CurrencyExchanger;

class CurrencyExchanger
{
    public function convert(): CurrencyConvert
    {
        return new CurrencyConvert();
    }
}
