<?php

namespace NetBull\HashidsBundle;

use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use NetBull\HashidsBundle\DependencyInjection\NetBullHashidsExtension;

class NetBullHashidsBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new NetBullHashidsExtension();
    }
}
