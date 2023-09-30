<?php

declare(strict_types=1);


namespace App\Back\BookingRequest\Application\Stats\Generate;


use App\Back\BookingRequest\Domain\StatsResponse;
use App\Back\Shared\Domain\Bus\Query\Response;

final class GenerateBookingRequestStatsResponse implements Response
{
    public function __construct(private readonly StatsResponse $statsDto) { }

    public function StatsDto(): StatsResponse
    {
        return $this->statsDto;
    }

}