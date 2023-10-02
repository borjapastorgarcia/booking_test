<?php

declare(strict_types=1);

namespace App\Back\BookingRequest\Application\Maximize\GenerateMaxCombinationProfit;

use App\Back\BookingRequest\Domain\BookingRequest;
use App\Back\BookingRequest\Domain\BookingRequestContract;
use App\Back\BookingRequest\Domain\MaximizeResponse;
use App\Back\BookingRequest\Domain\MaximizeResponseContract;
use App\Back\BookingRequest\Domain\StatsResponse;
use App\Back\BookingRequest\Domain\StatsResponseContract;
use App\Back\BookingRequest\Domain\ValidationErrorResponse;
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
        $validatedData = BookingRequest::fromJson($query->bookingStatsData());

        $maximizeResponse = MaximizeResponse::generateMaximizeProfitResponse(
            $validatedData
        );

        if (isset($maximizeResponse[MaximizeResponseContract::STATS_DATA][BookingRequestContract::PROFIT])) {
            unset($maximizeResponse[MaximizeResponseContract::STATS_DATA][BookingRequestContract::PROFIT]);
        }

        $statsResponse = StatsResponse::generateStats(
            BookingRequest::fromJson(json_encode($maximizeResponse[MaximizeResponseContract::STATS_DATA]))
        );

        return new MaximizeResponse(
            $maximizeResponse[MaximizeResponseContract::REQUEST_IDS],
            $maximizeResponse[MaximizeResponseContract::TOTAL_PROFIT],
            $statsResponse[StatsResponseContract::AVG_NIGHT],
            $statsResponse[StatsResponseContract::MIN_NIGHT],
            $statsResponse[StatsResponseContract::MAX_NIGHT]
        );
    }
}