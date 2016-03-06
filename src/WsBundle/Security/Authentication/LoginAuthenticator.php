<?php
declare(strict_types=1);
namespace WsBundle\Security\Authentication;

use AppBundle\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use WsBundle\Security\Jwt\TokenGenerator;

/**
 * Class LoginAuthenticator
 * @package WsBundle\Security
 */
class LoginAuthenticator extends AbstractGuardAuthenticator
{

    /** @var UserRepository */
    private $userRepository;

    /** @var TokenGenerator  */
    private $tokenGenerator;

    /**
     * ApiUserProvider constructor.
     * @param UserRepository $userRepository
     * @param TokenGenerator $tokenGenerator
     */
    public function __construct(UserRepository $userRepository, TokenGenerator $tokenGenerator)
    {
        $this->userRepository = $userRepository;
        $this->tokenGenerator = $tokenGenerator;
    }

    /**
     * @param Request $request
     * @param AuthenticationException|null $authException
     * @return JsonResponse
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = array(
            // you might translate this message
            'message' => 'Authentication Required'
        );

        return new JsonResponse($data, 401);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getCredentials(Request $request)
    {
        $payload = [];

        if ($request->getContentType() === 'application/json') {
            $payload = json_decode($request->getContent());
        }

        return [
            'username' => isset($payload['username']) ? $payload['username'] : null,
            'password' => isset($payload['password']) ? $payload['password'] : null,
        ];
    }


    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $this->userRepository->findByUsername($credentials['username']);
    }

    /**
     * Returns true if the credentials are valid.
     *
     * If any value other than true is returned, authentication will
     * fail. You may also throw an AuthenticationException if you wish
     * to cause authentication to fail.
     *
     * The *credentials* are the return value from getCredentials()
     *
     * @param mixed $credentials
     * @param UserInterface $user
     *
     * @return bool
     *
     * @throws AuthenticationException
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return JsonResponse
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse([
            'failure' => 'authentication error',
            'error' => $exception->getMessage(),
        ], 401);
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     * @return JsonResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $user = $token->getUser();
        $jwtToken = $this->tokenGenerator->createToken($user);

        return new JsonResponse(
            [
                'token' => $jwtToken->__toString()
            ],
            201
        );
    }

    /**
     * @return bool
     */
    public function supportsRememberMe()
    {
        return false;
    }
}