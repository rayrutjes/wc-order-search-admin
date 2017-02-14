<?php

/*
 * This file is part of AlgoliaIntegration library.
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace AlgoliaOrdersSearch\AlgoliaIntegration\Bus;

final class SimpleBus implements Bus
{
    /**
     * @var Handler[]
     */
    private $handlers = array();

    /**
     * @var HandlerNameResolver
     */
    private $nameResolver;

    /**
     * @param HandlerNameResolver $nameResolver
     */
    public function __construct(HandlerNameResolver $nameResolver)
    {
        $this->nameResolver = $nameResolver;
    }

    /**
     * @param Command $command
     */
    public function handle(Command $command)
    {
        $handler = $this->getHandlerForCommand($command);
        $handler->handle($command);
    }

    public function registerHandler(Handler $handler)
    {
        $handlerName = get_class($handler);
        if (isset($this->handlers[$handlerName])) {
            throw new \LogicException(sprintf('A handler named "%s" has already been registered.', $handlerName));
        }

        $this->handlers[$handlerName] = $handler;
    }

    /**
     * @param Command $command
     *
     * @return Handler
     */
    private function getHandlerForCommand(Command $command)
    {
        $name = $this->nameResolver->resolve($command);
        if (!isset($this->handlers[$name])) {
            throw new \RuntimeException(sprintf('No handler found for command "%s".', get_class($command)));
        }

        return $this->handlers[$name];
    }
}
