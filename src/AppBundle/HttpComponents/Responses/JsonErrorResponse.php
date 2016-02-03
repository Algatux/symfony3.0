<?php
declare(strict_types=1);

namespace AppBundle\HttpComponents\Responses;

use Symfony\Component\HttpFoundation\JsonResponse;

class JsonErrorResponse extends JsonResponse
{

    public function __construct($errorDescription, $errorDetail = "not available", $status = 500, $headers = array())
    {

        $responseData = [
            'error' => $errorDescription,
            'error.detail' => $errorDetail
        ];

        parent::__construct($responseData, $status, $headers);
    }

}
