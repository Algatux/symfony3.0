<?php
declare(strict_types=1);
namespace WsBundle\Controller;

use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AuthenticationController
 * @package WsBundle\Controller
 */
class AuthenticationController extends BaseController
{

    /**
     * @Route("/login", name="ws_login_check")
     * @Method({"POST"})
     *
     * @param Request $request
     * @return null
     */
    public function checkAction(Request $request)
    {
        /** FAKE */
        return null;
    }

    /**
     * @Route("/refresh", name="ws_token_refresh")
     * @Method({"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function refreshAction(Request $request)
    {
        $token = $this->get('ws.security_jwt.generator')->createToken($this->getUser());

        return new JsonResponse(
            [
                'token' => $token->__toString()
            ],
            201
        );
    }
    
}
