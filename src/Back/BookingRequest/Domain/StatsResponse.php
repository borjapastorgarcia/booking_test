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

    public static function generateStats(BookingRequestList $bookingRequestList): array
    {
        $profitPerNight = self::getUnitBookValues($bookingRequestList);

        return [
            StatsResponseContract::AVG_NIGHT => floatval(number_format(self::generateAverageProfit($profitPerNight, $bookingRequestList), 2)),
            StatsResponseContract::MIN_NIGHT => floatval(number_format(min($profitPerNight), 2)),
            StatsResponseContract::MAX_NIGHT => floatval(number_format(max($profitPerNight), 2))
        ];
    }

    private static function getUnitBookValues(BookingRequestList $bookingRequestList): array
    {
        $profitPerNight = [];
        foreach ($bookingRequestList->bookingRequests() as $bookingRequest) {
            $profitPerNight [] = ($bookingRequest->sellingRate() * ($bookingRequest->margin() / 100)) / $bookingRequest->nights();
        }
        return $profitPerNight;
    }

    private static function generateAverageProfit(array $profitPerNight, BookingRequestList $bookingRequestList): int|float
    {
        return array_reduce($profitPerNight, function ($sum, $object) {
                return $sum += $object;
            }) / count($bookingRequestList->bookingRequests());
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