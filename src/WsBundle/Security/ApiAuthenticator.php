<?php
declare(strict_types = 1);

namespace WsBundle\Security;

use Lcobucci\JWT\Parser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;
use WsBundle\Security\Authenticator\TokenRequestExtractor;
use WsBundle\Security\Jwt\AuthTokenValidatorInterface;

/**
 * Class ApiAuthenticator
 * @package WsBundle\Security
 */
class ApiAuthenticator implements SimplePreAuthenticatorInterface, AuthenticationFailureHandlerInterface
{

    /** @var AuthTokenValidatorInterface  */
    private $tokenValidator;

    /** @var TokenRequestExtractor  */
    private $tokenExtractor;

    /**
     * ApiAuthenticator constructor.
     * @param AuthTokenValidatorInterface $tokenValidator
     * @param TokenRequestExtractor $tokenRequestExtractor
     */
    public function __construct(
        AuthTokenValidatorInterface $tokenValidator,
        TokenRequestExtractor $tokenRequestExtractor
    )
    {
        $this->tokenValidator = $tokenValidator;
        $this->tokenExtractor = $tokenRequestExtractor;
    }

    /**
     * @param Request $request
     * @param $providerKey
     * @return PreAuthenticatedToken
     */
    public function createToken(Request $request, $providerKey)
    {
        try {
            $jwtToken = $this->tokenExtractor->extractTokenFromRequest($request);
        } catch (\Exception $e) {
            throw new BadCredentialsException('Credentials are not valid.');
        }

        if (false === $this->tokenValidator->validateToken($jwtToken)) {
            throw new BadCredentialsException('Credentials are not valid.');
        }

        return new PreAuthenticatedToken(
            'anon.',
            $jwtToken,
            $providerKey
        );
    }

    /**
     * @param TokenInterface $token
     * @param UserProviderInterface $userProvider
     * @param $providerKey
     * @return PreAuthenticatedToken
     */
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        if (!$userProvider instanceof ApiUserProvider) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The user provider must be an instance of ApiUserProvider (%s was given).',
                    get_class($userProvider)
                )
            );
        }

        $jwtToken = $token->getCredentials();

        $data = $jwtToken->getClaim('data');
        $user = $userProvider->loadUserByUsername(base64_decode($data->uidentifier));

        if (!$user->isEnabled() || $user->isExpired() || $user->isCredentialsExpired() || $user->isLocked()) {
            throw new CustomUserMessageAuthenticationException(
                sprintf('User is not able to operate')
            );
        }

        return new PreAuthenticatedToken(
            $user,
            $jwtToken,
            $providerKey,
            $user->getRoles()
        );
    }

    /**
     * @param TokenInterface $token
     * @param $providerKey
     * @return bool
     */
    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return Response
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new Response(strtr($exception->getMessageKey(), $exception->getMessageData()), 403);
    }

}
