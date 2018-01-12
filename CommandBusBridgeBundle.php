<?php

namespace Sprint\Bundle\CommandBusBridgeBundle;

use Sprint\Bundle\CommandBusBundle\DependencyInjection\Compiler\CommandMappingPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CommandBusBridgeBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new CommandMappingPass());
    }
}
