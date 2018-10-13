<?php
declare(strict_types = 1);

namespace AppBundle\Model;

use Money\Money;

class Product
{
    /** @var integer */
    private $id;

    /** @var string */
    private $sku;

    /** @var string */
    private $name;

    /** @var Money */
    private $price;

    /** @var \DateTime */
    private $createdAt;

    /**
     * Product constructor.
     *
     * @param Price  $price
     * @param string $sku
     * @param string $name
     *
     */
    public function __construct(Price $price, string $sku, string $name)
    {
        $this->price = $price;
        $this->sku = $sku;
        $this->name = $name;
        $this->createdAt = new \DateTime('now');
    }

    /**
     * @return string
     */
    public function sku():string
    {
        return $this->sku;
    }

    /**
     * @return Money
     */
    public function price():Price
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function name():string
    {
        return $this->name;
    }

    /**
     * @return \DateTime
     */
    public function created():\DateTime
    {
        return $this->createdAt;
    }
}