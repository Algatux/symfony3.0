<?php
declare(strict_types=1);
namespace Tests\unit\WsBundle\Security\Authentication;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use WsBundle\Security\Authentication\ApiAuthenticator;
use WsBundle\Security\Jwt\AuthTokenValidatorInterface;
use WsBundle\Security\Jwt\TokenRequestExtractor;

class ApiAuthenticatorTest extends \PHPUnit_Framework_TestCase
{

    public function test_create_token()
    {
        $this->markTestIncomplete('');
    }

    public function test_authenticate_token()
    {
        $this->markTestIncomplete('');
    }

    public function test_supports_token()
    {
        $this->markTestIncomplete('');
    }

    public function test_on_authentication_failure()
    {

        $validator = $this->prophesize(AuthTokenValidatorInterface::class);
        $extractor = $this->prophesize(TokenRequestExtractor::class);

        $authenticator = new ApiAuthenticator($validator->reveal(), $extractor->reveal());

        $response = $authenticator->onAuthenticationFailure(
            new Request(),
            new AuthenticationException('test authentication exception')
        );

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(403, $response->getStatusCode());

    }

    
}
