<?php
declare(strict_types=1);

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping\Index;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user",indexes={@Index(name="search_idx", columns={"username", "email"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends BaseUser implements JsonSerializable
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
     * @param string $username
     * @return $this|\FOS\UserBundle\Model\UserInterface
     */
    public function setUsername($username)
    {
        $this->username = $username;
        $this->setUsernameCanonical($username);

        return $this;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        $this->setEmailCanonical($email);

        return $this;
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

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return [
            'type' => 'user',
            'id' => $this->getId(),
            'attributes' => [
                'username' => $this->getUsernameCanonical(),
                'email' => $this->getEmailCanonical(),
                'firstname' => $this->registry->getName(),
                'lastname' => $this->registry->getSurname(),
            ]
        ];
    }
}
