<?php
declare(strict_types=1);
namespace WsBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TokenExtractor implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('ws.security_authenticator.token_request_extractor')) {
            return;
        }

        $definition = $container->findDefinition(
            'ws.security_authenticator.token_request_extractor'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'token_request_extractor.extractor'
        );
        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall(
                    'addExtractor',
                    array(new Reference($id), $attributes["alias"])
                );
            }
        }
    }
}