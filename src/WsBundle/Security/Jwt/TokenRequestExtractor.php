<?php
declare(strict_types=1);
namespace WsBundle\Security\Jwt;

use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Token;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use WsBundle\Security\Jwt\Extractors\TokenExtractorInterface;

/**
 * Class TokenRequestExtractor
 * @package WsBundle\Security\Authenticator
 */
class TokenRequestExtractor
{

    const REGEX_JWT_PAYLOAD = "/^([A-Za-z0-9-_=]+\.[A-Za-z0-9-_=]+\.[A-Za-z0-9-_.+=]+)$/";

    /** @var Parser  */
    private $jwtParser;

    /**
     * @var TokenExtractorInterface[]
     */
    private $extractors;

    /**
     * TokenRequestExtractor constructor.
     * @param Parser $jwtParser
     */
    public function __construct(Parser $jwtParser)
    {
        $this->jwtParser = $jwtParser;
        $this->extractors = [];
    }

    public function addExtractor(TokenExtractorInterface $extractor, string $alias)
    {
        $this->extractors[$alias] = $extractor;
    }

    /**
     * @param Request $request
     * @return Token
     */
    public function extractTokenFromRequest(Request $request): Token
    {
        foreach ($this->extractors as $extractor){
            if ($extractor->canHandle($request)){
                $token = $extractor->extract($request);
                return $this->jwtParser->parse($token);
            }
        }

        throw new BadCredentialsException('No Auth Token found.');
    }

}
