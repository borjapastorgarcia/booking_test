<?php

declare(strict_types=1);


namespace App\Back\BookingRequest\Application\Stats\Generate;


use App\Back\BookingRequest\Domain\BookingRequest;
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
        $response = StatsResponse::generateStats(
            BookingRequest::fromJson($query->statsData())
        );

        return new StatsResponse(
            $response['avg_night'],
            $response['min_night'],
            $response['max_night']
        );

    }
}