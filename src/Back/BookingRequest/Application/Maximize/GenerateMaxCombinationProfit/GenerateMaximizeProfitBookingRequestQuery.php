<?php

declare(strict_types=1);

namespace App\Back\BookingRequest\Application\Maximize\GenerateMaxCombinationProfit;

use App\Back\Shared\Domain\Bus\Query\Query;

final class GenerateMaximizeProfitBookingRequestQuery implements Query
{
    public function __construct(private readonly string $bookingStatsData) { }

    public function bookingStatsData(): string
    {
        return $this->bookingStatsData;
    }
}