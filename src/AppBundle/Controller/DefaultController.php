<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends BaseController
{
    /**
     * @Route("/", name="default_homepage")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
//        $user = $this->getEntityManager()->getRepository(User::class)->find(1);
//        $token = $this->get('ws.security_jwt.generator')->createToken($user);
//        $tokenString = $token->__toString();
//
//        $tokenRegenerated = $this->get('ws.security_jwt.token_validator')->getTokenFromRaw($tokenString);
//
//        $result = $this->get('ws.security_jwt.token_validator')->validateToken($tokenRegenerated);
//
//        dump(
//            $token,
//            $tokenString,
//            $tokenRegenerated,
//            $result
//            );

        // replace this example code with whatever you need
        return $this->render('AppBundle:default:index.html.twig',[]);
    }

    /**
     * @Route("/about", name="default_about")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function aboutAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('AppBundle:default:about.html.twig');
    }

    /**
     * @Route("/contacts", name="default_contacts")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function contactsAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('AppBundle:default:contacts.html.twig');
    }

}
