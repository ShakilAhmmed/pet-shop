<?php

namespace Shakilahmmed\CurrencyExchanger\DTO;

class CurrencyDTO
{
    private string $fromCurrency;

    private string $toCurrency;

    private float $amount;

    public function __construct($from, $to, $amount)
    {
        $this->fromCurrency = $from;
        $this->toCurrency = $to;
        $this->amount = $amount;
    }

    public function getFromCurrency(): string
    {
        return $this->fromCurrency;
    }

    public function getToCurrency(): string
    {
        return $this->toCurrency;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}
