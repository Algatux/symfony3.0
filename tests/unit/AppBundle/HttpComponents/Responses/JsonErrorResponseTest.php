<?php
declare(strict_types=1);

namespace unit\AppBundle\HttpComponents\Responses;

use AppBundle\HttpComponents\Responses\JsonErrorResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class JsonErrorResponseTest
 * @package unit\AppBundle\HttpComponents\Responses
 */
class JsonErrorResponseTest extends \PHPUnit_Framework_TestCase
{

    public function test_is_instance_of_JsonResponse()
    {
        $exception = new JsonErrorResponse('descrizione i prova');

        $this->assertInstanceOf(JsonResponse::class, $exception);
    }

    public function test_content_is_json()
    {
        $response = new JsonErrorResponse('descrizione i prova', ["name" => 'non va bene']);

        $this->assertJson($response->getContent());

        json_decode($response->getContent(),true);
        $this->assertEquals(JSON_ERROR_NONE, json_last_error());
    }

    public function test_content_has_error_description_and_details()
    {
        $response = new JsonErrorResponse('descrizione i prova', ["name" => 'non va bene']);

        $message = json_decode($response->getContent(),true);

        $this->assertArrayHasKey('error',$message);
        $this->assertArrayHasKey('error.detail',$message);

        $this->assertArrayHasKey('name',$message['error.detail']);

        $this->assertEquals('non va bene', $message['error.detail']['name']);
    }
    
}
