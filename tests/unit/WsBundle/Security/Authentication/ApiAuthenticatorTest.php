<?php
declare(strict_types=1);
namespace Tests\unit\WsBundle\Security\Authentication;

use AppBundle\Entity\User;
use Lcobucci\JWT\Token;
use Prophecy\Argument;
use stdClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\RememberMeToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use WsBundle\Security\ApiUserProvider;
use WsBundle\Security\Authentication\ApiAuthenticator;
use WsBundle\Security\Jwt\AuthTokenValidatorInterface;
use WsBundle\Security\Jwt\TokenRequestExtractor;

class ApiAuthenticatorTest extends \PHPUnit_Framework_TestCase
{

    public function test_create_token()
    {
        $jwtToken = new Token();

        $validator = $this->prophesize(AuthTokenValidatorInterface::class);
        $extractor = $this->prophesize(TokenRequestExtractor::class);

        $extractor->extractTokenFromRequest(Argument::type(Request::class))
            ->shouldBeCalledTimes(1)
            ->willReturn($jwtToken);

        $validator->validateToken($jwtToken)
            ->shouldBeCalledTimes(1)
            ->willReturn(true);

        $authenticator = new ApiAuthenticator($validator->reveal(), $extractor->reveal());

        $token = $authenticator->createToken(new Request(), 'key');

        $this->assertInstanceOf(PreAuthenticatedToken::class, $token);
        $this->assertEquals('anon.',$token->getUser());
        $this->assertInstanceOf(Token::class,$token->getCredentials());
        $this->assertEquals('key',$token->getProviderKey());
    }

    public function test_authenticate_token()
    {
        $user = new User();
        $user->setEnabled(true);

        $data = new stdClass();
        $data->uidentifier = 'username';
        $jwtToken = $this->prophesize(Token::class);
        $jwtToken->getClaim('data')
            ->shouldBeCalledTimes(1)
            ->willReturn($data);

        $token = new PreAuthenticatedToken('anon.', $jwtToken->reveal(),'key');

        $provider = $this->prophesize(ApiUserProvider::class);
        $provider->loadUserByUsername($data->uidentifier)
            ->shouldBeCalledTimes(1)
            ->willReturn($user);

        $authenticator = $this->getBasicAuthenticatorMock();

        $preAuthToken = $authenticator
            ->authenticateToken($token, $provider->reveal(), 'key');

        $this->assertInstanceOf(PreAuthenticatedToken::class, $preAuthToken);
        $this->assertInstanceOf(Token::class,$preAuthToken->getCredentials());
        $this->assertEquals('key',$preAuthToken->getProviderKey());
        $this->assertTrue(is_array($preAuthToken->getRoles()));

    }

    /**
     * @param $user
     *
     * @dataProvider userDataProvider
     */
    public function test_authenticate_token_throws_exception_if_no_loggable_user_found($user)
    {

        $data = new stdClass();
        $data->uidentifier = 'username';
        $jwtToken = $this->prophesize(Token::class);
        $jwtToken->getClaim('data')
            ->shouldBeCalledTimes(1)
            ->willReturn($data);

        $token = new PreAuthenticatedToken('anon.', $jwtToken->reveal(),'key');

        $provider = $this->prophesize(ApiUserProvider::class);
        $provider->loadUserByUsername($data->uidentifier)
            ->shouldBeCalledTimes(1)
            ->willReturn($user);

        $authenticator = $this->getBasicAuthenticatorMock();

        $this->expectException(CustomUserMessageAuthenticationException::class);
        $preAuthToken = $authenticator
            ->authenticateToken($token, $provider->reveal(), 'key');

    }

    public function test_supports_token()
    {
        $token = new PreAuthenticatedToken('anon.', '', 'key');

        $authenticator = $this->getBasicAuthenticatorMock();

        $this->assertTrue($authenticator->supportsToken($token, 'key'));
        $this->assertFalse($authenticator->supportsToken($token, 'false_key'));
        $this->assertFalse(
            $authenticator->supportsToken(
                new RememberMeToken(new User(),'k', 's'),
                'key'
                )
        );
    }

    public function test_on_authentication_failure()
    {

        $authenticator = $this->getBasicAuthenticatorMock();

        $response = $authenticator->onAuthenticationFailure(
            new Request(),
            new AuthenticationException('test authentication exception')
        );

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(403, $response->getStatusCode());

    }

    public function userDataProvider()
    {
        return [
            [$this->getUser(1,1)],
            [$this->getUser(1,0,1)],
            [$this->getUser(1,0,0,1)],
        ];
    }

    private function getUser(
        $enabled = true,
        $expired = false,
        $locked = false,
        $notFound = false
    )
    {
        if ($notFound) {
            return null;
        }

        $user = new User();
        $user->setEnabled($enabled);
        $user->setExpired($expired);
        $user->setLocked($locked);

        return $user;
    }
    
    private function getBasicAuthenticatorMock()
    {
        $validator = $this->prophesize(AuthTokenValidatorInterface::class);
        $extractor = $this->prophesize(TokenRequestExtractor::class);

        return new ApiAuthenticator($validator->reveal(), $extractor->reveal());
    }


}
