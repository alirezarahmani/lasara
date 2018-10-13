<?php
declare(strict_types=1);

namespace AppBundle\Model;

use Assert\Assertion;
use Money\Currency;

class ProductCurrency
{
    /** @var Currency */
    private $currency;
    const EUR = 'EUR';
    const CNY = 'CNY';

    const CURRENCIES = [
            self::CNY,
            self::EUR,
        ];

    public function __construct(string $currency)
    {
        Assertion::inArray($currency, self::CURRENCIES, 'wrong currency, ' . $currency . ' is not valid.');
        $this->currency = new Currency($currency);
    }

    public function currency():string
    {
        return $this->currency->getCode();
    }

}