<?php

declare(strict_types=1);


namespace App\Back\BookingRequest\Application\Stats\Generate;

use App\Back\Shared\Domain\Bus\Query\Query;

final class GenerateBookingRequestStatsQuery implements Query
{
    public function __construct(private readonly string $statsData) { }

    public function statsData(): string
    {
        return $this->statsData;
    }

}