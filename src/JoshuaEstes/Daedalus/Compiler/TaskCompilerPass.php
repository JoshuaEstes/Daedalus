<?php

namespace JoshuaEstes\Daedalus\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 */
class CommandCompilerPass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('command')) {
            return;
        }

        $commands = $container->findTaggedServiceIds('commands');
    }
}
