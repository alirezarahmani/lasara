<?php
declare(strict_types = 1);

namespace AppBundle\Infrastructure\Validation;


class ProductApiInputValidation extends LazyAssert implements ValidationInterface
{
    /**
     * @param array $inputs
     * @throws \InvalidArgumentException
     */
    public function validate(array $inputs)
    {
        $assert = $this->initArray($inputs);
        $assert
            ->thatInArray('name')
            ->notEmpty('make sure have value')
            ->string('it should be string')
            ->minLength(4, 'too short')
            ->maxLength(255, 'too long');
        $assert
            ->thatInArray('sku')
            ->notEmpty('make sure have value')
            ->string('it should be string')
            ->minLength(4, 'too short')
            ->maxLength(255, 'too long');
        $assert
            ->thatInArray('price')
            ->notEmpty('make sure have value')
            ->isArray('it should be array');

        $assert->verifyNow();
    }

}