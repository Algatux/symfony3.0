<?php
//declare(strict_types=1);

namespace Tests\unit\WsBundle\Security;


use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use WsBundle\Security\ApiKeyAuthenticator;
use WsBundle\Security\ApiKeyUserProvider;
use WsBundle\Security\ApiKeyValidator;

class ApiKeyAuthenticatorTest extends \PHPUnit_Framework_TestCase
{

    public function test_createToken_will_return_a_pre_authenticated_token()
    {
        $request = new Request();
        $request->headers = new HeaderBag([
            'apikey' => '32db4703-558f-4363-a409-733f80b95e8c'
        ]);

        $authenticator = new ApiKeyAuthenticator(new ApiKeyValidator());

        $preAuthToken = $authenticator
            ->createToken($request, 'test_provider_key');

        $this->assertEquals('anon.', $preAuthToken->getUser());
        $this->assertEquals('32db4703-558f-4363-a409-733f80b95e8c', $preAuthToken->getCredentials());
        $this->assertEquals('test_provider_key', $preAuthToken->getProviderKey());
    }

    public function test_authenticateToken_will_decorate_the_pre_authenticated_token()
    {
        $preAuthToken = new PreAuthenticatedToken('anon.', '32db4703-558f-4363-a409-733f80b95e8c', 'test_provider_key');

        $user = new User();
        $user->setUsername('user_username');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setEnabled(true);

        $userProvider = $this->getUserProvider($preAuthToken, $user);

        $authenticator = new ApiKeyAuthenticator(new ApiKeyValidator());
        $authenticator->authenticateToken($preAuthToken, $userProvider->reveal(), 'test_provider_key');
    }

    public function test_authenticateToken_will_throw_exception_user_not_found_for_apikey()
    {
        $preAuthToken = new PreAuthenticatedToken('anon.', 'api_key_test', 'test_provider_key');

        $user = new User();
        $user->setUsername('user_username');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setEnabled(false);

        $userProvider = $this->getUserProvider($preAuthToken, $user);

        $authenticator = new ApiKeyAuthenticator(new ApiKeyValidator());
        $this->setExpectedException(CustomUserMessageAuthenticationException::class);
        $authenticator->authenticateToken($preAuthToken, $userProvider->reveal(), 'test_provider_key');
    }

    public function test_authenticateToken_will_throw_exception_if_user_is_not_enabled()
    {
        $preAuthToken = new PreAuthenticatedToken('anon.', 'api_key_test', 'test_provider_key');

        $user = new User();
        $user->setUsername('user_username');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setEnabled(false);

        $userProvider = $this->prophesize(ApiKeyUserProvider::class);
        $userProvider
            ->getUsernameForApiKey($preAuthToken->getCredentials())
            ->shouldBeCalledTimes(1)
            ->willReturn(null);

        $authenticator = new ApiKeyAuthenticator(new ApiKeyValidator());
        $this->setExpectedException(CustomUserMessageAuthenticationException::class);
        $authenticator->authenticateToken($preAuthToken, $userProvider->reveal(), 'test_provider_key');
    }

    public function test_authenticateToken_will_throw_exception_if_user_is_expired()
    {
        $preAuthToken = new PreAuthenticatedToken('anon.', 'api_key_test', 'test_provider_key');

        $user = new User();
        $user->setUsername('user_username');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setEnabled(true);
        $user->setExpired(true);

        $userProvider = $this->getUserProvider($preAuthToken, $user);

        $authenticator = new ApiKeyAuthenticator(new ApiKeyValidator());
        $this->setExpectedException(CustomUserMessageAuthenticationException::class);
        $authenticator->authenticateToken($preAuthToken, $userProvider->reveal(), 'test_provider_key');
    }

    /**
     * @param $preAuthToken
     * @param $user
     * @return \Prophecy\Prophecy\ObjectProphecy
     */
    private function getUserProvider($preAuthToken, $user)
    {
        $userProvider = $this->prophesize(ApiKeyUserProvider::class);
        $userProvider
            ->getUsernameForApiKey($preAuthToken->getCredentials())
            ->shouldBeCalledTimes(1)
            ->willReturn($user->getUsername());
        $userProvider
            ->loadUserByUsername($user->getUsername())
            ->shouldBeCalledTimes(1)
            ->willReturn($user);
        return $userProvider;
    }

}