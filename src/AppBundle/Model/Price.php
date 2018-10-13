<?php
declare(strict_types = 1);

namespace AppBundle\Model;

use AppBundle\Infrastructure\Service\CurrencyExchangerInterface;
use Money\Currency;
use Money\Money;

class Price
{
    /** @var Money  */
    private $money;

    public function __construct($amount, ProductCurrency $euroChinaCurrency)
    {
        // according to Grasp Information Expert pattern
        // https://en.wikipedia.org/wiki/GRASP_(object-oriented_design)#Information_expert
        $this->money = new Money(intval($amount), new Currency($euroChinaCurrency->currency()));
    }

    public function convertToCurrency(Currency $currency, CurrencyExchangerInterface $currencyExchanger)
    {
        if ($this->money->getCurrency()->getCode() !== $currency->getCode()) {
            // if i return money then abstraction leaks out
            // why I cast money to int
            //https://github.com/moneyphp/money
            $this->money = new Money(
                $currencyExchanger->exchangedAmount(
                    intval($this->money->getAmount()),
                    $this->money->getCurrency(),
                    $currency
                ),
                $currency
            );
        }

    }

    public function getAmount()
    {
        return $this->money->getAmount();
    }

    public function getCurrency():string
    {
        //if i return currency then, abstraction leaks out
        return $this->money->getCurrency()->getCode();
    }
}