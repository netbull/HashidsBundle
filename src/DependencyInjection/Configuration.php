<?php

namespace NetBull\HashidsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('netbull_hashids');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('salt')->defaultValue('%kernel.secret%')->end()
                ->scalarNode('min_hash_length')->defaultValue(0)->end()
                ->scalarNode('alphabet')->defaultValue('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890')->end()
            ->end();

        return $treeBuilder;
    }
}
