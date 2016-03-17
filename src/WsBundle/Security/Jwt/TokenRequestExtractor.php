<?php
declare(strict_types=1);
namespace WsBundle\Security\Jwt;

use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Token;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

/**
 * Class TokenRequestExtractor
 * @package WsBundle\Security\Authenticator
 */
class TokenRequestExtractor
{

    const REGEX_JWT_HEADER = "/^Bearer[\s]([A-Za-z0-9-_=]+\.[A-Za-z0-9-_=]+\.[A-Za-z0-9-_.+=]+)$/";

    /** @var Parser  */
    private $jwtParser;

    /**
     * TokenRequestExtractor constructor.
     * @param Parser $jwtParser
     */
    public function __construct(Parser $jwtParser)
    {
        $this->jwtParser = $jwtParser;
    }

    /**
     * @param Request $request
     * @return Token
     */
    public function extractTokenFromRequest(Request $request)
    {
        $authHeader = $request->headers->get('Authorization', null, true);

        if (null === $authHeader) {
            throw new BadCredentialsException('No Auth header found.');
        }

        if (! preg_match(self::REGEX_JWT_HEADER, $authHeader, $matches) || ! isset($matches[1])) {
            throw new BadCredentialsException('No Auth Token found.');
        }

        return $this->jwtParser->parse($matches[1]);
    }

}
