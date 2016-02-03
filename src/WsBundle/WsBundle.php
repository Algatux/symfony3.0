<?php

namespace WsBundle;

use WsBundle\DependencyInjection\WsBundleExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class WsBundle extends Bundle
{

    public function getContainerExtension()
    {
        return new WsBundleExtension();
    }

}
