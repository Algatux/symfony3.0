<?php
//declare(strict_types=1);
namespace Tests\unit\WsBundle\Security;

use WsBundle\Security\ApiKeyValidator;

class ApiKeyValidatorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider keysProvider
     * @param $key
     * @param $expectedResult
     */
    public function test_validate_api_key($key, $expectedResult)
    {
        $validator = new ApiKeyValidator();

        $this->assertEquals($expectedResult, $validator->validateKey($key));
    }

    public function keysProvider()
    {
        return [
            ['knjas-123sfa-12321354-4364363', false],
            ['api-key', false],
            ['32db4703-558f-4363-a409-733f80b95e8c', true],
        ];
    }

}
