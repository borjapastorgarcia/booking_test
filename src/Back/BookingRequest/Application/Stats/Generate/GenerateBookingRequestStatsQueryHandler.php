<?php

declare(strict_types=1);


namespace App\Back\BookingRequest\Application\Stats\Generate;

use App\Back\BookingRequest\Domain\BookingRequestList;
use App\Back\BookingRequest\Domain\StatsResponse;
use App\Back\BookingRequest\Domain\ValidationErrorResponse;
use App\Back\Shared\Domain\Bus\Query\QueryHandler;
use JsonException;

final class GenerateBookingRequestStatsQueryHandler implements QueryHandler
{
    public function __construct() { }

    /**
     * @throws ValidationErrorResponse
     * @throws JsonException
     */
    public function __invoke(GenerateBookingRequestStatsQuery $query): StatsResponse
    {
        return StatsResponse::generateStats(
            BookingRequestList::fromJson($query->statsData())
        );
    }
}