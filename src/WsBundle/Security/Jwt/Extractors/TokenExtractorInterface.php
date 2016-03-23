<?php
declare(strict_types=1);
namespace WsBundle\Security\Jwt\Extractors;

use Symfony\Component\HttpFoundation\Request;

/**
 * Interface TokenExtractorInterface
 * @package WsBundle\Security\Jwt\Extractors
 */
interface TokenExtractorInterface
{

    /**
     * @param Request $request
     * @return bool
     */
    public function canHandle(Request $request): bool;

    /**
     * @param Request $request
     * @return string
     */
    public function extract(Request $request): string;
    
}
