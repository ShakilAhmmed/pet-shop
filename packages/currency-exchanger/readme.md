## Currency Exchanger

## Installation [currently using as local dependency]

#### update your composer.json as below

```bash
    "repositories": [
        {
            "type": "path",
            "url": "packages/currency-exchanger",
            "options": {
                "symlink": true
            }
        }
    ],
    "require": {
       "shakilahmmed/currency-exchanger": "@dev"
    }
```

## Run

```bash
    composer update
    php artisan vendor:publish --provider="Shakilahmmed\CurrencyExchanger\CurrencyExchangerServiceProvider" --tag="config"
```

### Usage

```bash
    CurrencyConversion::convert()
    ->from('EUR')
    ->to('USD')
    ->amount(100)
    ->get();
```

### All Possible Currency Codes

```bash
    USD
    JPY
    BGN
    CZK
    DKK
    GBP
    HUF
    PLN
    RON
    SEK
    CHF
    ISK
    NOK
    HRK
    TRY
    AUD
    BRL
    CAD
    CNY
    HKD
    IDR
    ILS
    INR
    KRW
    MXN
    MYR
    NZD
    PHP
    SGD
    THB
    ZAR
```
