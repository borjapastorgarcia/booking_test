<?php

declare(strict_types=1);


namespace App\Back\Shared\infrastructure\Bus\Query;


use App\Back\Shared\Domain\Bus\Query\Query;
use App\Back\Shared\Domain\Bus\Query\QueryBus;
use App\Back\Shared\Domain\Bus\Query\Response;
use App\Back\Shared\infrastructure\Bus\HandlerBuilder;
use InvalidArgumentException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class InMemoryQueryBus implements QueryBus
{
    private MessageBus $bus;

    public function __construct(iterable $queryHandlers)
    {
        try {
            $this->bus = new MessageBus([
                new HandleMessageMiddleware(
                    new HandlersLocator(
                        HandlerBuilder::fromCallables($queryHandlers),
                    ),
                ),
            ]);
        } catch (\ReflectionException $e) {
        }
    }

    public function ask(Query $query): Response|null
    {
        try {
            /** @var HandledStamp $stamp */
            $stamp = $this->bus->dispatch($query)->last(HandledStamp::class);

            return $stamp->getResult();
        } catch (NoHandlerForMessageException $e) {
            throw new InvalidArgumentException(sprintf('The query has not a valid handler: %s', $query::class));
        }
    }
}