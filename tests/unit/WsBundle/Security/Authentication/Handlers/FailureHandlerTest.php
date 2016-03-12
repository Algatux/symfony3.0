<?php
declare(strict_types=1);
namespace Tests\unit\WsBundle\Security\Authentication\Handlers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use WsBundle\Security\Authentication\Handlers\FailureHandler;

class FailureHandlerTest extends \PHPUnit_Framework_TestCase
{

    public function test_on_authentication_failure()
    {

        $handler = new FailureHandler();

        $response = $handler->onAuthenticationFailure(
            new Request(),
            new AuthenticationException('test authentication exception')
        );

        $this->assertEquals(401, $response->getStatusCode());

        $response_array = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('failure', $response_array);
        $this->assertArrayHasKey('failure', $response_array);

        $this->assertEquals('authentication error',$response_array['failure']);
        $this->assertEquals('test authentication exception',$response_array['error']);

    }
    
}
