<?php

namespace WsBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use WsBundle\DependencyInjection\CompilerPass\TokenExtractor;
use WsBundle\DependencyInjection\ContainerBuilder\SerializerCompilerPass;
use WsBundle\DependencyInjection\WsBundleExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class WsBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new TokenExtractor());
    }

    public function getContainerExtension()
    {
        return new WsBundleExtension();
    }

}
