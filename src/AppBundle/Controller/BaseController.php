<?php
declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class BaseController
 * @package AppBundle\Controller
 */
class BaseController extends Controller
{

    /**
     * @param string|null $name
     * @return EntityManagerInterface
     */
    protected function getEntityManager(string $name = null): EntityManagerInterface
    {
        return $this->getDoctrine()->getManager($name);
    }

    /**
     * @return User|null
     */
    protected function getUser()
    {
        return parent::getUser();
    }

}