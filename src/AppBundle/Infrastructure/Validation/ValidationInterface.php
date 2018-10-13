<?php
declare(strict_types = 1);

namespace AppBundle\Infrastructure\Validation;

interface ValidationInterface
{
    /**
     * @param array $inputs
     * @throws \InvalidArgumentException
     */
    public function validate(array $inputs);
}