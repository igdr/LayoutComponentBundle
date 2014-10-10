<?php

namespace Igdr\Bundle\LayoutComponentBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html//cookbook-bundles-extension-config-class}
 */
class ComponentConfiguration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('places');

        $this->addResourcesSection($rootNode);

        return $treeBuilder;
    }

    /**
     * Adds `components` section.
     *
     * @param ArrayNodeDefinition $node
     */
    private function addResourcesSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('places')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->arrayNode('components')
                                ->useAttributeAsKey('name')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('controller')
                                            ->cannotBeEmpty()
                                        ->end()
                                        ->scalarNode('ordering')
                                        ->end()
                                        ->arrayNode('routes')
                                            ->prototype('scalar')
                                            ->end()
                                        ->end()
                                ->end()
                            ->end()
                        ->end()
                ->end()
            ->end();
    }
}
