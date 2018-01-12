<?php

namespace Sprint\Bundle\CommandBusBundle\DependencyInjection\Compiler;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CommandMappingPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $commandServices = $container->findTaggedServiceIds('console.command');

        $mapping = [];
        foreach ($commandServices as $id => $tags) {
            $definition = $container->getDefinition($id);

            $class = $container->getParameterBag()->resolveValue($definition->getClass());
            $r = new \ReflectionClass($class);
            $instance = $r->newInstanceWithoutConstructor();
            if (!$instance instanceof Command) {
                continue;
            }

            $r = new \ReflectionObject($instance);
            while ('Symfony\Component\Console\Command\Command' !== $r->getName()) {
                $r = $r->getParentClass();
            }

            $m = $r->getMethod('__construct');
            $m->setAccessible(true);
            $m->invoke($instance);

            $name = $instance->getName();

            $mapping[$name] = $id;
        }

        $container->setParameter('console.command.ids.mappings', $mapping);
    }
}
