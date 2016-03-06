<?php
declare(strict_types=1);
namespace WsBundle\Security\Jwt;

use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\ValidationData;

/**
 * Class TokenValidator
 * @package WsBundle\Security\Jwt
 */
class TokenValidator implements AuthTokenValidatorInterface
{

    /** @var ValidationData  */
    private $validationData;

    /** @var string  */
    private $secret;

    /** @var Signer  */
    private $signer;

    /**
     * TokenValidator constructor.
     * @param ValidationData $validationData
     * @param Signer $signer
     * @param string $secret
     * @param array $config
     */
    public function __construct(
        ValidationData $validationData,
        Signer $signer,
        string $secret,
        array $config
    )
    {
        $this->validationData = $validationData;
        $this->signer = $signer;
        $this->secret = $secret;

        $this->validationData->setAudience($config['audience']);
        $this->validationData->setIssuer($config['issuer']);
        $this->validationData->setId($config['appid']);
    }

    /**
     * @param Token $token
     * @return bool
     */
    public function validateToken(Token $token): bool
    {
        return $token->validate($this->validationData) && $token->verify($this->signer, $this->secret);
    }

}
