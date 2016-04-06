<?php
declare(strict_types=1);
namespace WsBundle\Security\Jwt\Extractors;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

/**
 * Class AbstractExtractor
 * @package WsBundle\Security\Jwt\Extractors
 */
abstract class AbstractExtractor implements TokenExtractorInterface
{

    /**
     * @param Request $request
     * @return string
     */
    abstract protected function getTokenFromRequest(Request $request): string;

    /**
     * @return string
     */
    abstract protected function getRegexMatcher(): string;

    /**
     * @param Request $request
     * @return bool
     */
    public function canHandle(Request $request): bool
    {
        $token = $this->getTokenFromRequest($request);

        if (
            null === $token ||
            ! preg_match($this->getRegexMatcher(), $token, $matches) ||
            ! isset($matches[1])
        ) {
            return false;
        }

        return true;
    }

    /**
     * @param Request $request
     * @return string
     */
    public function extract(Request $request): string
    {
        $token = $this->getTokenFromRequest($request);

        if (
            null === $token ||
            ! preg_match($this->getRegexMatcher(), $token, $matches) ||
            ! isset($matches[1])
        ) {
            throw new BadCredentialsException('No Auth Token found.');
        }

        return $matches[1];
    }
}