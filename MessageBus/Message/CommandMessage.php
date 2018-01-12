<?php

namespace Sprint\Bundle\CommandBusBridgeBundle\MessageBus\Message;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

final class CommandMessage
{
    protected $command;
    protected $input;
    protected $output;

    public function __construct(
        $command,
        array $input,
        OutputInterface $output = null
    ) {
        $this->command = $command;
        $this->input = new ArrayInput($input);
        $this->input->setInteractive(false);
        if (!$output) {
            $output = new NullOutput();
        }
        $this->output = $output;
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @return ArrayInput
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @return OutputInterface
     */
    public function getOutput()
    {
        return $this->output;
    }
}
