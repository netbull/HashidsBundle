<?php

namespace NetBull\HashidsBundle\DependencyInjection;

use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class NetBullHashidsExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     * @return void
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        // set parameters with the default settings so they'll be available in the service definition yml
        $varNames = ['salt', 'min_hash_length', 'alphabet'];
        foreach($varNames as $varName) {
            $container->setParameter('netbull_hashids.' . $varName, $config[$varName]);
        }

        $loader->load('services.yaml');
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return 'netbull_hashids';
    }
}
