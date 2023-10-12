<?php

declare(strict_types=1);

namespace App\Back\BookingRequest\Application\Maximize\GenerateMaxCombinationProfit;

use App\Back\BookingRequest\Domain\BookingRequestList;
use App\Back\BookingRequest\Domain\MaximizeResponse;
use App\Back\Shared\Domain\Bus\Query\QueryHandler;
use JsonException;

final class GenerateMaximizeProfitBookingRequestQueryHandler implements QueryHandler
{
    public function __construct() { }

    /**
     * @throws JsonException
     */
    public function __invoke(GenerateMaximizeProfitBookingRequestQuery $query): MaximizeResponse
    {
        return MaximizeResponse::generateMaximizeProfitResponse(
            BookingRequestList::fromJson(
                $query->bookingStatsData()
            )
        );
    }
}