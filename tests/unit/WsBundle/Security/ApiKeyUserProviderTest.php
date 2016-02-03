<?php
//declare(strict_types=1);

namespace Tests\unit\WsBundle\Security;

use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use Prophecy\Argument;
use WsBundle\Entity\ApiKey;
use WsBundle\Repository\ApiKeyRepository;
use WsBundle\Security\ApiKeyUserProvider;

class ApiKeyUserProviderTest extends \PHPUnit_Framework_TestCase
{

    const TEST_KEY = 'apikey_hua9sgd790yuc56gahg234ash32d';
    const TEST_USERNAME = 'user_user_from_apikey';

    public function test_getUsernameForApiKey()
    {

        $userRepo = $this->prophesize(UserRepository::class);
        $apiKeyRepo = $this->prophesize(ApiKeyRepository::class);

        $user = $this->prophesize(User::class);
        $user->getUsername()
            ->shouldBeCalledTimes(1)
            ->willReturn(self::TEST_USERNAME);

        $apiKey = $this->prophesize(ApiKey::class);
        $apiKey->getUser()
            ->shouldBeCalledTimes(1)
            ->willReturn($user->reveal());

        $apiKeyRepo
            ->findByApiKey(self::TEST_KEY)
            ->shouldBeCalledTimes(1)
            ->willReturn($apiKey->reveal());

        $userProvider = new ApiKeyUserProvider($userRepo->reveal(), $apiKeyRepo->reveal());
        $username = $userProvider->getUsernameForApiKey(self::TEST_KEY);

        $this->assertEquals(self::TEST_USERNAME, $username);

    }

    public function test_loadUserByUsername()
    {

        $userRepo = $this->prophesize(UserRepository::class);
        $apiKeyRepo = $this->prophesize(ApiKeyRepository::class);

        $userRepo
            ->findByUsername(self::TEST_USERNAME)
            ->shouldBeCalledTimes(1)
            ->willReturn(new User());

        $userProvider = new ApiKeyUserProvider($userRepo->reveal(), $apiKeyRepo->reveal());
        $userProvider->loadUserByUsername(self::TEST_USERNAME);

    }

    public function test_supportsClass()
    {
        $userRepo = $this->prophesize(UserRepository::class);
        $apiKeyRepo = $this->prophesize(ApiKeyRepository::class);

        $userProvider = new ApiKeyUserProvider($userRepo->reveal(), $apiKeyRepo->reveal());

        $this->assertTrue($userProvider->supportsClass(User::class));
        $this->assertFalse($userProvider->supportsClass(\Symfony\Component\Security\Core\User\User::class));
    }

}
