<?php
declare(strict_types=1);

namespace WsBundle\HttpComponents\Responses;

use Symfony\Component\HttpFoundation\JsonResponse;

class JsonApiDocumentResponse extends JsonResponse
{

    public function __construct($data = null, $status = 200, $headers = array())
    {

        $document = [
            "data" => $data,
        ];

        parent::__construct($document, $status, $headers);
    }

}
