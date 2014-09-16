<?php

namespace JoshuaEstes\Daedalus;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Configuration of build file
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('daedalus');

        $rootNode
            ->append($this->addTasksNode());

        return $treeBuilder;
    }

    protected function addTasksNode()
    {
        $builder = new TreeBuilder();
        $node    = $builder->root('tasks');

        $node
            ->isRequired()
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('task')
            ->prototype('array')
                ->children()
                    ->scalarNode('description')->defaultNull()->end()
                    ->append($this->addCommandsNode())
                ->end()
            ->end();

        return $node;
    }

    protected function addCommandsNode()
    {
        $builder = new TreeBuilder();
        $node    = $builder->root('commands');

        $node
            ->isRequired()
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('name')
            ->prototype('array')
                ->children()
                    ->scalarNode('command')->isRequired()->end()
                    ->append($this->addArgumentsNode())
                ->end()
            ->end();

        return $node;
    }

    protected function addArgumentsNode()
    {
        $builder = new TreeBuilder();
        $node    = $builder->root('arguments');

        $node
            ->prototype('scalar')
            ->end();

        return $node;
    }
}
