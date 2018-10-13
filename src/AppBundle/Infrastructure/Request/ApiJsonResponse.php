<?php
declare(strict_types = 1);

namespace AppBundle\Infrastructure\Request;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiJsonResponse implements ApiResponseInterface
{
    public function error($errors, int $status)
    {
        $status = $status < 200 ? Response::HTTP_BAD_REQUEST : $status;
        return (new JsonResponse(
            [
            'Data' => [],
            'Message' => $errors
            ],
            $status
        ))->send();
    }

    public function success($data = [], int $status = Response::HTTP_ACCEPTED)
    {
        return (new JsonResponse(
            [
                'Data' => $data,
                'Message' => ''
            ],
            $status
        ))->send();
    }
}
