<?php

namespace Sprint\Bundle\CommandBusBridgeBundle\MessageBus\Message\Handler;

use Sprint\Bundle\CommandBusBridgeBundle\MessageBus\Message\CommandMessage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\DebugFormatterHelper;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\ProcessHelper;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\DependencyInjection\Container;

class CommandMessageHandler
{
    /** @var Container */
    protected $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param CommandMessage $message
     * @throws \Exception
     */
    public function handle(CommandMessage $message)
    {
        $mapping = $this->container->getParameter('console.command.ids.mappings');
        $command = $message->getCommand();
        if (!isset($mapping[$command])) {
            throw new \InvalidArgumentException(sprintf(
                'Command "%s" does not exist as console command.',
                $command
            ));
        }
        $id = $mapping[$command];

        /** @var Command $command */
        $command = $this->container->get($id);
        $command->setHelperSet(
            new HelperSet([
                new FormatterHelper(),
                new DebugFormatterHelper(),
                new ProcessHelper(),
                new QuestionHelper(),
            ])
        );

        $command->run($message->getInput(), $message->getOutput());
    }
}
