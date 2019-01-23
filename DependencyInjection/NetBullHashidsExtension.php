<?php

namespace NetBull\HashidsBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class NetBullHashidsExtension
 * @package NetBull\HashidsBundle\DependencyInjection
 */
class NetBullHashidsExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        // set parameters with the default settings so they'll be available in the service definition yml
        $varNames = ['salt', 'min_hash_length', 'alphabet'];
        if (!empty($config['form_types']) && !empty($config['form_types']['ajax'])) {
            foreach($varNames as $varName) {
                $container->setParameter('netbull_hashids.' . $varName, $config[$varName]);
            }
        }
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return 'netbull_hashids';
    }
}
