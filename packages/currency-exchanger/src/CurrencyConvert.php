<?php

namespace Shakilahmmed\CurrencyExchanger;

class CurrencyConvert
{
    private const EUR = 'EUR';

    protected $apiBaseUri = "https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml";

    private $fromCurrency;

    private $toCurrency;

    private $amount;

    private $attributes = [];

    public function __construct()
    {
    }

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

    public function get()
    {
        $xml = simpleXML_load_file($this->apiBaseUri, "SimpleXMLElement", LIBXML_NOCDATA);
        $xml = json_encode($xml);
        $xmlArray = json_decode($xml, true);
        $result = 0;

        foreach ($xmlArray['Cube'] ?? [] as $child) {
            if (!empty($child['@attributes']['time'])) {
                $this->attributes['time'] = $child['@attributes']['time'];
                foreach ($child['Cube'] ?? [] as $node) {
                    if (!empty($node['@attributes'])) {
                        $this->attributes['rates'][$node['@attributes']['currency']] = $node['@attributes']['rate'];
                    }
                }
            }
        }

        if ($this->isSameCurrency()) {
            return 0;
        }

        if ($this->isFromCurrencyEur()) {
            $result = $this->amount * $this->cost($this->toCurrency);
        }

        if ($this->isToCurrencyEur()) {
            $result = $this->amount / $this->cost($this->fromCurrency);
        }

        if (!$this->isFromCurrencyEur() && !$this->isToCurrencyEur()) {
            $result = $this->amount / $this->cost($this->fromCurrency) * $this->cost($this->toCurrency);
        }

        return (float)number_format($result, 2, '.', '');
    }

    public function isSameCurrency(): bool
    {
        return $this->fromCurrency === $this->toCurrency;
    }

    public function isFromCurrencyEur(): bool
    {
        return $this->fromCurrency === self::EUR;
    }

    public function isToCurrencyEur(): bool
    {
        return $this->toCurrency === self::EUR;
    }


    private function cost(string $currency): float
    {
        return $this->attributes['rates'][$currency] ?? reset($this->attributes)['rates'][$currency] ?? 0;
    }
}
