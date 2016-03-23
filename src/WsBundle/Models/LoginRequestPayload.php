<?php
declare(strict_types=1);
namespace WsBundle\Models;

/**
 * Class LoginRequestPayload
 * @package WsBundle\Models
 */
class LoginRequestPayload
{

    /** @var string  */
    private $username;

    /** @var string  */
    private $password;

    /**
     * LoginRequestPayload constructor.
     * @param string $username
     * @param string $password
     */
    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

}