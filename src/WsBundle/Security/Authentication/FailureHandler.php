<?php
declare(strict_types=1);
namespace WsBundle\Security\Authentication;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

/**
 * Class FailureHandler
 * @package WsBundle\Security\Authentication
 */
class FailureHandler implements AuthenticationFailureHandlerInterface
{

    /**
     * This is called when an interactive authentication attempt fails. This is
     * called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param Request $request
     * @param AuthenticationException $exception
     *
     * @return JsonResponse|Response The response to return, never null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
    {
        return new JsonResponse([
            'failure' => 'authentication error',
            'error' => $exception->getMessage(),
        ], 401);
    }
}
