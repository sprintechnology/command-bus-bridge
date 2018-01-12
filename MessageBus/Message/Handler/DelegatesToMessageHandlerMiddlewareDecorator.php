<?php

namespace Sprint\Bundle\CommandBusBridgeBundle\MessageBus\Message\Handler;

use Sprint\Bundle\CommandBusBridgeBundle\MessageBus\Message\CommandMessage;
use SimpleBus\Message\Bus\Middleware\MessageBusMiddleware;
use SimpleBus\Message\Handler\DelegatesToMessageHandlerMiddleware;

class DelegatesToMessageHandlerMiddlewareDecorator implements MessageBusMiddleware
{
    protected $delegator;
    protected $commandMessageHandler;

    public function __construct(
        DelegatesToMessageHandlerMiddleware $delegator,
        CommandMessageHandler $commandMessageHandler
    ) {
        $this->delegator = $delegator;
        $this->commandMessageHandler = $commandMessageHandler;
    }

    public function handle($message, callable $next)
    {
        if ($message instanceof CommandMessage) {
            $this->commandMessageHandler->handle($message);
            $next($message);
        } else {
            $this->delegator->handle($message, $next);
        }
    }
}
