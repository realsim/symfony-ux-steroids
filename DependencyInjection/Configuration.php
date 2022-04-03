<?php

namespace Symfony\UX\Steroids\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('ux_steroids');
        $rootNode = $treeBuilder->getRootNode();

        $this->addPaginationSection($rootNode);

        return $treeBuilder;
    }

    private function addPaginationSection(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->children()
                ->arrayNode('pagination')
                    ->beforeNormalization()
                        ->ifTrue(static function ($v) {
                            return is_array($v) && !array_key_exists('paginators', $v) && !array_key_exists('paginator', $v);
                        })
                        ->then(static function ($v) {
                            $excludedKeys = ['default_paginator'];
                            $paginator = [];
                            foreach ($v as $key => $value) {
                                if (isset($excludedKeys[$key])) {
                                    continue;
                                }

                                $paginator[$key] = $v[$key];
                                unset($v[$key]);
                            }

                            $v['default_paginator'] = isset($v['default_paginator']) ? (string) $v['default_paginator'] : 'default';
                            $v['paginators'] = [$v['default_paginator'] => $paginator];

                            return $v;
                        })
                    ->end()
                    ->info('pagination configuration')
                    ->children()
                        ->scalarNode('default_paginator')->end()
                    ->end()
                    ->fixXmlConfig('paginator')
                    ->append($this->getPaginatorsNode())
                ->end()
            ->end();
    }

    private function getPaginatorsNode(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('paginators');
        $node = $treeBuilder->getRootNode();

        $paginatorNode = $node
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('name')
            ->arrayPrototype();
        assert($paginatorNode instanceof ArrayNodeDefinition);

        $paginatorNode
            ->children()
                ->scalarNode('page_parameter')->defaultValue('page')->end()
                ->scalarNode('limit_parameter')->defaultValue('limit')->end()
                ->integerNode('on_page')->defaultValue(30)->end()
                ->integerNode('max_on_page')->defaultValue(100)->end()
            ->end();

        return $node;
    }
}
