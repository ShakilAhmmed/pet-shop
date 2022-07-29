<?php

namespace Shakilahmmed\CurrencyExchanger\Services;

use Shakilahmmed\CurrencyExchanger\CurrencyConvert;

class ConversionClient
{
    protected $apiBaseUri = "https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml";

    private const EUR = 'EUR';

    private CurrencyConvert $currencyConvert;

    private $xmlResponse;
    private $attributes;

    private function __construct(CurrencyConvert $currencyConvert)
    {
        $this->currencyConvert = $currencyConvert;
        $xml = simpleXML_load_file($this->apiBaseUri, "SimpleXMLElement", LIBXML_NOCDATA);
        $xml = json_encode($xml);
        $this->xmlResponse = json_decode($xml, true);
        $this->formatAttributes();
    }

    public static function using(CurrencyConvert $currencyConvert): ConversionClient
    {
        return new static($currencyConvert);
    }

    private function formatAttributes(): void
    {
        foreach ($this->xmlResponse['Cube'] ?? [] as $child) {
            if (!empty($child['@attributes']['time'])) {
                $this->attributes['time'] = $child['@attributes']['time'];
                foreach ($child['Cube'] ?? [] as $node) {
                    if (!empty($node['@attributes'])) {
                        $this->attributes['rates'][$node['@attributes']['currency']] = $node['@attributes']['rate'];
                    }
                }
            }
        }
    }

    public function getRate()
    {
        $rate = 0;
        $amount = $this->currencyConvert->getAmount();
        $fromCurrency = $this->currencyConvert->getFromCurrency();
        $toCurrency = $this->currencyConvert->getToCurrency();

        if ($this->isSameCurrency()) {
            return 0;
        }

        if ($this->isFromCurrencyEur()) {
            $rate = $amount * $this->cost($toCurrency);
        }

        if ($this->isToCurrencyEur()) {
            $rate = $amount / $this->cost($fromCurrency);
        }

        if (!$this->isFromCurrencyEur() && !$this->isToCurrencyEur()) {
            $rate = $amount / $this->cost($fromCurrency) * $this->cost($toCurrency);
        }

        return (float)number_format($rate, 2, '.', '');
    }

    public function isSameCurrency(): bool
    {
        return $this->currencyConvert->getFromCurrency() === $this->currencyConvert->getToCurrency();
    }

    public function isFromCurrencyEur(): bool
    {
        return $this->currencyConvert->getFromCurrency() === self::EUR;
    }

    public function isToCurrencyEur(): bool
    {
        return $this->currencyConvert->getToCurrency() === self::EUR;
    }


    private function cost(string $currency): float
    {
        return $this->attributes['rates'][$currency] ?? reset($this->attributes)['rates'][$currency] ?? 0;
    }
}
