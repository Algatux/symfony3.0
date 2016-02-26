<?php
declare(strict_types=1);
namespace WsBundle\Security\Jwt;

use AppBundle\Entity\User;
use Carbon\Carbon;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer;
use WsBundle\Security\TokenInterface;

/**
 * Class TokenGenerator
 * @package WsBundle\Security\Jwt
 */
class TokenGenerator implements AuthTokenFactoryInterface
{

    /** @var Builder  */
    private $tokenBuilder;

    /** @var Signer  */
    private $signer;

    /** @var string  */
    private $secret;

    /** @var array  */
    private $config;

    /**
     * TokenGenerator constructor.
     * @param Builder $tokenBuilder
     * @param Signer $signer
     * @param string $secret
     * @param array $config
     */
    public function __construct(Builder $tokenBuilder, Signer $signer, string $secret, array $config)
    {
        $this->tokenBuilder = $tokenBuilder;
        $this->signer = $signer;
        $this->secret = $secret;
        $this->config = $config;
    }

    /**
     * @param User $user
     * @return TokenInterface
     */
    public function createToken(User $user)
    {
        $issued = Carbon::now();
        $expire = Carbon::now()->addSeconds((int) $this->config['expire']);
        $notBefore = Carbon::now()->addSeconds((int) $this->config['notbefore']);

        return $this->tokenBuilder
            ->setIssuer($this->config['issuer'])
            ->setAudience($this->config['audience'])
            ->setId($this->config['appid'], true)
            ->setIssuedAt($issued->getTimestamp())
            ->setNotBefore($notBefore->getTimestamp())
            ->setExpiration($expire->getTimestamp())
            ->set('data', [
                "uid" => $user->getId(),
                "uidentifier" => base64_encode($user->getUsername()),
            ])
            ->sign($this->signer, $this->secret)
            ->getToken();
    }

}
