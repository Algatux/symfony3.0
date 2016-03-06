<?php
declare(strict_types=1);
namespace WsBundle\Controller;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\User;
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
     * @Route("/test", name="ws_test")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function testAction(Request $request)
    {
        return new JsonResponse(["ciao"]);
    }

    /**
     * @Route("/login", name="ws_login_check")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function checkAction(Request $request)
    {
        return new JsonResponse(['lalal']);
    }

    /**
     * @Route("/users", name="ws_users_list")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function usersAction(Request $request)
    {
        $users = $this->getEntityManager()
            ->getRepository(User::class)->findAll();

        return new JsonResponse(['data' => $users]);
    }
    
}
