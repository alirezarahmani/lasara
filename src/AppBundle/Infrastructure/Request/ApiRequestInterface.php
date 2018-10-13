<?php
declare(strict_types = 1);

namespace AppBundle\Infrastructure\Request;

use Symfony\Component\HttpFoundation\Request;

interface ApiRequestInterface
{
    public function getRequest():Request;
}
