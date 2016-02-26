<?php
declare(strict_types=1);

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use WsBundle\Entity\ApiKey;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var UserRegistry
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\UserRegistry", mappedBy="user", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $registry;

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return UserRegistry
     */
    public function getRegistry()
    {
        return $this->registry;
    }

    /**
     * @param UserRegistry $registry
     */
    public function setRegistry($registry)
    {
        $this->registry = $registry;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        if (! $this->registry) {
            return '';
        }

        return $this->registry->getName() . " " . $this->registry->getSurname();
    }

}