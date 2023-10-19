<?php

declare(strict_types=1);


namespace App\Back\Shared\Infrastructure\Bus\Command;


use App\Back\Shared\Domain\Bus\Command\Command;
use App\Back\Shared\Domain\Bus\Command\CommandBus;
use App\Back\Shared\Infrastructure\Bus\HandlerBuilder;
use InvalidArgumentException;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

final class InMemoryCommandBus implements CommandBus
{
    private MessageBus $bus;

    public function __construct(
        iterable $commandHandlers
    )
    {
        try {
            $this->bus = new MessageBus([
                new HandleMessageMiddleware(
                    new HandlersLocator(
                        HandlerBuilder::fromCallables($commandHandlers),
                    ),
                ),
            ]);
        } catch (\ReflectionException $e) {
        }
    }

    public function dispatch(Command $command): void
    {
        try {
            $this->bus->dispatch($command);
        } catch (NoHandlerForMessageException $e) {
            throw new InvalidArgumentException(sprintf('The command has not a valid handler: %s', $command::class));
        } catch (HandlerFailedException $e) {
            throw $e->getPrevious();
        }
    }
}