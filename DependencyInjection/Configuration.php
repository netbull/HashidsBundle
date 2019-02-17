<?php

namespace NetBull\HashidsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package NetBull\HashidsBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('netbull_core');

        $rootNode
            ->children()
                ->scalarNode('salt')->defaultValue('%kernel.secret%')->end()
                ->scalarNode('min_hash_length')->defaultValue(0)->end()
                ->scalarNode('alphabet')->defaultValue('')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
