<?php
declare(strict_types=1);
namespace WsBundle\Security\Authentication\Handlers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use WsBundle\Security\Jwt\TokenGenerator;

/**
 * Class SuccessHandler
 * @package WsBundle\Security\Authentication
 */
class SuccessHandler implements AuthenticationSuccessHandlerInterface
{

    /** @var TokenGenerator  */
    private $tokenGenerator;

    /**
     * SuccessHandler constructor.
     * @param TokenGenerator $tokenGenerator
     */
    public function __construct(TokenGenerator $tokenGenerator)
    {
        $this->tokenGenerator = $tokenGenerator;
    }

    /**
     * This is called when an interactive authentication attempt succeeds. This
     * is called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param Request $request
     * @param TokenInterface $token
     *
     * @return JsonResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token): JsonResponse
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

}
