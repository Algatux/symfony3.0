<?php
declare(strict_types=1);
namespace WsBundle\Security\Authentication;

use AppBundle\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimpleFormAuthenticatorInterface;
use WsBundle\Exceptions\Request\InvalidLoginRequestPayload;
use WsBundle\Models\LoginRequestPayload;
use WsBundle\Security\Jwt\TokenGenerator;

/**
 * Class JsonLoginAuthenticator
 * @package WsBundle\Security
 */
class JsonLoginAuthenticator implements SimpleFormAuthenticatorInterface
{

    /** @var UserRepository */
    private $userRepository;

    /** @var TokenGenerator  */
    private $tokenGenerator;

    /** @var UserPasswordEncoderInterface  */
    private $encoder;

    /**
     * ApiUserProvider constructor.
     * @param UserRepository $userRepository
     * @param TokenGenerator $tokenGenerator
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(
        UserRepository $userRepository,
        TokenGenerator $tokenGenerator,
        UserPasswordEncoderInterface $encoder
    )
    {
        $this->userRepository = $userRepository;
        $this->tokenGenerator = $tokenGenerator;
        $this->encoder = $encoder;
    }

    /**
     * @param TokenInterface $token
     * @param UserProviderInterface $userProvider
     * @param $providerKey
     * @return UsernamePasswordToken
     */
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        try {
            $user = $userProvider->loadUserByUsername($token->getUsername());
        } catch (UsernameNotFoundException $e) {
            throw new CustomUserMessageAuthenticationException('Invalid username or password');
        }

        $passwordValid = $this->encoder->isPasswordValid($user, $token->getCredentials());

        if ($passwordValid) {
            return new UsernamePasswordToken(
                $user,
                $user->getPassword(),
                $providerKey,
                $user->getRoles()
            );
        }

        throw new CustomUserMessageAuthenticationException('Invalid username or password');
    }

    /**
     * @param TokenInterface $token
     * @param $providerKey
     * @return bool
     */
    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof UsernamePasswordToken && $token->getProviderKey() === $providerKey;
    }

    /**
     * @param Request $request
     * @param $username
     * @param $password
     * @param $providerKey
     * @return UsernamePasswordToken
     */
    public function createToken(Request $request, $username, $password, $providerKey)
    {
        try{
            $payload = $this->getRequestBody($request);
        } catch (InvalidLoginRequestPayload $e){
            throw new CustomUserMessageAuthenticationException($e->getMessage());
        }

        return new UsernamePasswordToken($payload->getUsername(), $payload->getPassword(), $providerKey);
    }

    /**
     * @param Request $request
     * @return LoginRequestPayload
     * @throws InvalidLoginRequestPayload
     */
    private function getRequestBody(Request $request)
    {
        if ($request->getContentType() === 'json') {
            $payload = json_decode($request->getContent(), true);

            $credentials = [
                'username' => isset($payload['username']) ? $payload['username'] : null,
                'password' => isset($payload['password']) ? $payload['password'] : null,
            ];

            return new LoginRequestPayload($credentials['username'], $credentials['password']);
        }

        throw new InvalidLoginRequestPayload('Login request is not in the correct format. Required Json');

    }
}