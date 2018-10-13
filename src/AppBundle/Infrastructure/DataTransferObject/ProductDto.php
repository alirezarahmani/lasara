<?php
declare(strict_types = 1);

namespace AppBundle\Infrastructure\DataTransferObject;

use AppBundle\Infrastructure\Service\FixerCurrencyExchanger;
use AppBundle\Model\ProductCurrency;
use AppBundle\Model\Product;
use Money\Currency;

// Data Transfer Object
// https://martinfowler.com/eaaCatalog/dataTransferObject.html
class ProductDto
{
    /**
     * @var string
     */
    private $products;

    /**
     * @param Product[] $products
     */
    public function __construct(array $products)
    {
        $this->products =$products;
    }

    /**
     * @param ProductCurrency $baseCurrency
     *
     * @return array
     */
    public function getArrayCopy(ProductCurrency $baseCurrency):array
    {
        $products = [];
        $exchanger = new FixerCurrencyExchanger();
        // needs for converting
        $baseCurrency = new Currency($baseCurrency->currency());

        foreach ($this->products as $product) {
            $product->price()->convertToCurrency($baseCurrency, $exchanger);
            $products[] = array(
                'name'   => $product->name(),
                'sku' => $product->sku(),
                'amount' => $product->price()->getAmount(),
                'currency' => $product->price()->getCurrency()
            );
        }

        return $products;
    }

}
