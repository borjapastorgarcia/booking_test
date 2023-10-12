<?php

declare(strict_types=1);

namespace App\Back\BookingRequest\Domain;

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

    public static function generateStats(BookingRequestList $bookingRequestList): self
    {
        $profitsPerNight = $bookingRequestList->getUnitBookValues();

        return self::create(
            BookingRequestList::generateAverageProfit($profitsPerNight, count($bookingRequestList->bookingRequests())),
            min($profitsPerNight),
            max($profitsPerNight)
        );
    }


    public function toArray()
    {
        return [
            StatsResponseContract::AVG_NIGHT => $this->avgNight(),
            StatsResponseContract::MIN_NIGHT => $this->minNight(),
            StatsResponseContract::MAX_NIGHT => $this->maxNight()
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