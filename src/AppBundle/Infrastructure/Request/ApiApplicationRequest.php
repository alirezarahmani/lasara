<?php
declare(strict_types = 1);

namespace AppBundle\Infrastructure\Request;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;

class ApiApplicationRequest extends ParameterBag implements ApiRequestInterface
{
    /**
     * @var Request
     */
    private $request;

    public function __construct(RequestStack $request)
    {
        $this->request = $request;
    }

    public function getRequest():Request
    {
        return $this->request->getCurrentRequest();
    }

}
