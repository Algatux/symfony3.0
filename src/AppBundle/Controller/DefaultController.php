<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="default_homepage")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('AppBundle:default:index.html.twig');
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
