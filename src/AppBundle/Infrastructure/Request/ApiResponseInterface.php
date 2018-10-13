<?php
declare(strict_types = 1);

namespace AppBundle\Infrastructure\Request;

interface ApiResponseInterface
{
    public function error($errors, int $status);
}
