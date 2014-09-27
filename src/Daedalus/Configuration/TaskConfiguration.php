<?php

namespace Daedalus\Configuration;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Configuration of build file
 */
class TaskConfiguration implements ConfigurationInterface
{
    /**
     * @inheritdoc
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('daedalus');

        $rootNode
            ->append($this->addTasksNode());

        return $treeBuilder;
    }

    /**
     * Returns the tasks node
     */
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
                    ->arrayNode('requires')
                        ->useAttributeAsKey('task')
                        ->prototype('scalar')->end()
                    ->end()
                    ->append($this->addCommandsNode())
                ->end()
            ->end();

        return $node;
    }

    /**
     * Commands Node
     */
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
                    ->append($this->addOptionsNode())
                ->end()
            ->end();

        return $node;
    }

    /**
     * Arguments that are passed to a command
     */
    protected function addArgumentsNode()
    {
        $builder = new TreeBuilder();
        $node    = $builder->root('arguments');

        $node
            ->prototype('variable')
            ->end();

        return $node;
    }

    /**
     * Options that are passed to a command
     */
    protected function addOptionsNode()
    {
        $builder = new TreeBuilder();
        $node    = $builder->root('options');

        $node
            ->prototype('variable')
            ->end();

        return $node;
    }
}
