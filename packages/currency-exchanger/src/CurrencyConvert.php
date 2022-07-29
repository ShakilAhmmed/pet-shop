<?php

namespace Shakilahmmed\CurrencyExchanger;

use Shakilahmmed\CurrencyExchanger\DTO\CurrencyDTO;
use Shakilahmmed\CurrencyExchanger\Services\ConversionClient;

class CurrencyConvert
{
    private string $fromCurrency;

    private string $toCurrency;

    private float $amount;

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

    public function get(): float|int
    {
        return ConversionClient::using(
            new CurrencyDTO($this->fromCurrency, $this->toCurrency, $this->amount)
        )->getRate();
    }
}
