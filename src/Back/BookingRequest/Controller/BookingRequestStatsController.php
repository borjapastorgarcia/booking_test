<?php

declare(strict_types=1);


namespace App\Back\BookingRequest\Controller;


use App\Back\BookingRequest\Application\Stats\Generate\GenerateBookingRequestStatsQuery;
use App\Back\BookingRequest\Domain\StatsResponse;
use App\Back\BookingRequest\Infrastructure\Http\GenerateStatsResponder;
use App\Back\Shared\Domain\Bus\Query\QueryBus;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class BookingRequestStatsController extends AbstractController
{
    public function __construct(
        private readonly GenerateStatsResponder $responder,
        private readonly QueryBus $queryBus)
    {
    }

    public function __invoke(Request $request): Response
    {
        try {
            /** @var StatsResponse $stats */
            $stats = $this->queryBus->ask(
                new GenerateBookingRequestStatsQuery(
                    $request->getContent()
                )
            );
            $this->responder->loadStats($stats);

        } catch (Exception $exception) {
            $this->responder->loadError($exception->getMessage());
        }
        return $this->responder->response();
    }
}