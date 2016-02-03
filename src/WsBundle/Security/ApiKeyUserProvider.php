<?php
declare(strict_types = 1);

namespace WsBundle\Security;

use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use WsBundle\Repository\ApiKeyRepository;

/**
 * Class ApiKeyUserProvider
 * @package AppBundle\Security
 */
class ApiKeyUserProvider implements UserProviderInterface
{

    /** @var ApiKeyRepository */
    private $apiKeyRepository;

    /** @var UserRepository */
    private $userRepository;

    /**
     * ApiKeyUserProvider constructor.
     * @param UserRepository $userRepository
     * @param ApiKeyRepository $apiKeyRepository
     */
    public function __construct(UserRepository $userRepository, ApiKeyRepository $apiKeyRepository)
    {
        $this->userRepository = $userRepository;
        $this->apiKeyRepository = $apiKeyRepository;
    }

    /**
     * @param string $apiKey
     * @return null|string
     */
    public function getUsernameForApiKey($apiKey)
    {
        // Look up the username based on the token in the database, via
        // an API call, or do something entirely different
        $apiKeyEntity = $this->apiKeyRepository->findByApiKey($apiKey);

        if (null === $apiKeyEntity) {
            return null;
        }

        return $apiKeyEntity->getUser()->getUsername();
    }

    /**
     * @param string $username
     * @return User|null
     */
    public function loadUserByUsername($username)
    {
        return $this->userRepository->findByUsername($username);
    }

    public function refreshUser(UserInterface $user)
    {
        // this is used for storing authentication in the session
        // but in this example, the token is sent in each request,
        // so authentication can be stateless. Throwing this exception
        // is proper to make things stateless
        throw new UnsupportedUserException();
    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass($class)
    {
        return User::class === $class;
    }

}
