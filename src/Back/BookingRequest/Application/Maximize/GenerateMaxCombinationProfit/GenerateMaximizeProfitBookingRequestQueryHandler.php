<?php

declare(strict_types=1);

namespace App\Back\BookingRequest\Application\Maximize\GenerateMaxCombinationProfit;

use App\Back\BookingRequest\Application\Maximize\MaximizeProfitResponse;
use App\Back\BookingRequest\Application\ValidationErrorResponse;
use App\Back\BookingRequest\Domain\BookingRequestList;
use App\Back\Shared\Domain\Bus\Query\QueryHandler;
use JsonException;

final class GenerateMaximizeProfitBookingRequestQueryHandler implements QueryHandler
{
    public function __construct() { }

    /**
     * @throws JsonException
     * @throws ValidationErrorResponse
     */
    public function __invoke(GenerateMaximizeProfitBookingRequestQuery $query): MaximizeProfitResponse
    {
        return MaximizeProfitResponse::generate(
            BookingRequestList::fromJson(
                $query->bookingStatsData()
            )
        );
    }
}