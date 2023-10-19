<?php

declare(strict_types=1);

namespace App\Back\BookingRequest\UI\Controller;

use App\Back\BookingRequest\Application\Maximize\GenerateMaxCombinationProfit\GenerateMaximizeProfitBookingRequestQuery;
use App\Back\BookingRequest\Application\Maximize\MaximizeProfitResponse;
use App\Back\BookingRequest\Infrastructure\Http\GenerateMaxProfitCombinationResponder;
use App\Back\Shared\Domain\Bus\Query\QueryBus;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class BookingRequestMaximizeController extends AbstractController
{
    public function __construct(
        private readonly GenerateMaxProfitCombinationResponder $responder,
        private readonly QueryBus $queryBus
    )
    {
    }

    public function __invoke(Request $request): Response
    {
        try {
            /** @var MaximizeProfitResponse $bestCombinationResponse */
            $bestCombinationResponse = $this->queryBus->ask(
                new GenerateMaximizeProfitBookingRequestQuery(
                    $request->getContent()
                )
            );
            $this->responder->loadBestCombination($bestCombinationResponse);
            return $this->responder->response();
        } catch (Exception $exception) {
            $this->responder->loadError($exception->getMessage());
            return $this->responder->response();
        }
    }
}