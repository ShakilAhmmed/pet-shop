<?php

namespace Shakilahmmed\CurrencyExchanger;

use Shakilahmmed\CurrencyExchanger\Services\ConversionClient;

class CurrencyConvert
{
    private $fromCurrency;

    private $toCurrency;

    private $amount;

    public function from($fromCurrency): CurrencyConvert
    {
        $this->fromCurrency = $fromCurrency;
        return $this;
    }

    public function to($toCurrency): CurrencyConvert
    {
        $this->toCurrency = $toCurrency;
        return $this;
    }

    public function amount(float $amount): CurrencyConvert
    {
        $this->amount = $amount;
        return $this;
    }

    public function getFromCurrency()
    {
        return $this->fromCurrency;
    }

    public function getToCurrency()
    {
        return $this->toCurrency;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function get(): float|int
    {
        return ConversionClient::using($this)->getRate();
    }
}
