<?php

declare(strict_types=1);

namespace App\Back\BookingRequest\Application\Stats;

use App\Back\BookingRequest\Domain\BookingRequestList;
use App\Back\BookingRequest\Domain\StatsResponseContract;
use App\Back\Shared\Domain\Bus\Query\Response;

final class StatsResponse implements Response
{
    public function __construct(
        private readonly float $avgNight,
        private readonly float $minNight,
        private readonly float $maxNight
    )
    {
    }

    public static function create(
        float $avgNight,
        float $minNight,
        float $maxNight
    ): self
    {
        return new self(
            floatval(number_format($avgNight, 2)),
            floatval(number_format($minNight, 2)),
            floatval(number_format($maxNight, 2))
        );
    }

    public static function generate(BookingRequestList $bookingRequestList): self
    {
        $profitsPerNight = $bookingRequestList->getUnitBookValues();

        return self::create(
            BookingRequestList::generateAverageProfit($profitsPerNight, count($bookingRequestList->bookingRequests())),
            min($profitsPerNight),
            max($profitsPerNight)
        );
    }

    public function toArray(): array
    {
        return [
            StatsResponseContract::avg_night() => $this->avgNight(),
            StatsResponseContract::min_night() => $this->minNight(),
            StatsResponseContract::max_night() => $this->maxNight()
        ];
    }

    public function avgNight(): float
    {
        return $this->avgNight;
    }

    public function minNight(): float
    {
        return $this->minNight;
    }

    public function maxNight(): float
    {
        return $this->maxNight;
    }
}