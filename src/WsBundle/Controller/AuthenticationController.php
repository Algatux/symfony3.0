<?php
declare(strict_types=1);
namespace WsBundle\Controller;

use AppBundle\Controller\BaseController;
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
     * @Route("/test", name="ws_test")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function testAction(Request $request)
    {
        return new JsonResponse(["ciao"]);
    }
    
}
