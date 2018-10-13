<?php
declare(strict_types = 1);

namespace AppBundle\Infrastructure\Service;

use Money\Currency;

interface CurrencyExchangerInterface
{
    public function exchangedAmount($amount, Currency $currency, Currency $toCurrency);
}