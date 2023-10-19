<?php

declare(strict_types=1);


namespace App\Back\BookingRequest\Application\Stats\Generate;

use App\Back\BookingRequest\Application\Stats\StatsResponse;
use App\Back\BookingRequest\Application\ValidationErrorResponse;
use App\Back\BookingRequest\Domain\BookingRequestList;
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
        return StatsResponse::generate(
            BookingRequestList::fromJson($query->statsData())
        );
    }
}