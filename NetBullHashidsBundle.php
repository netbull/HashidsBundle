<?php

namespace NetBull\HashidsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

use NetBull\CoreBundle\DependencyInjection\NetBullHashidsExtension;

/**
 * Class NetBullHashidsBundle
 * @package NetBull\HashidsBundle
 */
class NetBullHashidsBundle extends Bundle
{
    /**
     * @return NetBullHashidsExtension|null|\Symfony\Component\DependencyInjection\Extension\ExtensionInterface
     */
    public function getContainerExtension()
    {
        return new NetBullHashidsExtension();
    }
}
