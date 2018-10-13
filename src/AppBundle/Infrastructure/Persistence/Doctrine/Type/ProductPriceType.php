<?php
/*
 * This file is part of the prooph/php-ddd-cargo-sample.
 * (c) Alexander Miertsch <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 01.03.14 - 17:25
 */

namespace AppBundle\Infrastructure\Persistence\Doctrine\Type;

use AppBundle\Model\Price;
use AppBundle\Model\ProductCurrency;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\TextType;

final class ProductPriceType extends TextType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'product_price';
    }

    /**
     * @param string $value
     * @param AbstractPlatform $platform
     * @return Price
     * @throws \Exception
     */
    public function convertToPhpValue($value, AbstractPlatform $platform): Price
    {
        if (null === $value) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        $price = json_decode($value, true);
        return new Price($price['value'], new ProductCurrency($price['currency']));
    }

    /**
     * @param Price $value
     * @param AbstractPlatform $platform
     * @return string|null
     * @throws \Doctrine\DBAL\Types\ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform):string
    {
        if (!is_a($value, Price::class)) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        return json_encode(['value' => $value->getAmount(), 'currency' => $value->getCurrency()]);
    }
} 