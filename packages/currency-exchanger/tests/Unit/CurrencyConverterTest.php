<?php


use Shakilahmmed\CurrencyExchanger\CurrencyConvert;

it('will convert EUR to USD', function () {
    $conversion = new CurrencyConvert();
    $rate = $conversion->from('EUR')
        ->to('USD')
        ->amount(100)
        ->get();
    $this->assertEquals($rate, 101.98);
});
