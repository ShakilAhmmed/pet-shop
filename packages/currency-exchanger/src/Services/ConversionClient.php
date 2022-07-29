<?php

namespace Shakilahmmed\CurrencyExchanger\Services;

use Shakilahmmed\CurrencyExchanger\CurrencyConvert;
use Shakilahmmed\CurrencyExchanger\DTO\CurrencyDTO;

class ConversionClient
{
    protected $apiBaseUri = "https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml";

    private const EUR = 'EUR';

    private CurrencyDTO $currencyDTO;

    private $xmlResponse;
    private $attributes;

    private function __construct(CurrencyDTO $currencyDTO)
    {
        $this->currencyDTO = $currencyDTO;
        $xml = simpleXML_load_file($this->apiBaseUri, "SimpleXMLElement", LIBXML_NOCDATA);
        $xml = json_encode($xml);
        $this->xmlResponse = json_decode($xml, true);
        $this->formatAttributes();
    }

    public static function using(CurrencyDTO $currencyDTO): ConversionClient
    {
        return new static($currencyDTO);
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

    public function getRate(): float|int
    {
        $rate = 0;
        $amount = $this->currencyDTO->getAmount();
        $fromCurrency = $this->currencyDTO->getFromCurrency();
        $toCurrency = $this->currencyDTO->getToCurrency();

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
        return $this->currencyDTO->getFromCurrency() === $this->currencyDTO->getToCurrency();
    }

    public function isFromCurrencyEur(): bool
    {
        return $this->currencyDTO->getFromCurrency() === self::EUR;
    }

    public function isToCurrencyEur(): bool
    {
        return $this->currencyDTO->getToCurrency() === self::EUR;
    }

    private function cost(string $currency): float
    {
        return $this->attributes['rates'][$currency] ?? reset($this->attributes)['rates'][$currency] ?? 0;
    }
}
