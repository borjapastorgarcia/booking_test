<?php

declare(strict_types=1);

namespace App\Back\BookingRequest\Domain;

use App\Back\Shared\Domain\Bus\Query\Response;

final class MaximizeResponse implements Response
{
    public function __construct(
        private readonly array $requestIds,
        private readonly float $totalProfit,
        private readonly float $avgNight,
        private readonly float $minNight,
        private readonly float $maxNight
    )
    {
    }

    public static function generateMaximizeProfitResponse(BookingRequestList $bookingRequestList): array
    {
        $validBookingRequestCombinations = self::findCombinationGreatestProfit(self::removeOverlappedCombinations($bookingRequestList));
        return [
            MaximizeResponseContract::REQUEST_IDS => array_column($validBookingRequestCombinations, BookingRequestContract::REQUEST_ID),
            MaximizeResponseContract::TOTAL_PROFIT => $validBookingRequestCombinations[BookingRequestContract::PROFIT],
            MaximizeResponseContract::STATS_DATA => $validBookingRequestCombinations
        ];
    }

    public static function hasConflict(array $booking1, array $booking2): bool
    {
        $checkIn1 = strtotime($booking1[BookingRequestContract::CHECK_IN]);
        $checkOut1 = strtotime("+" . $booking1[BookingRequestContract::NIGHTS] . " days", $checkIn1);

        $checkIn2 = strtotime($booking2[BookingRequestContract::CHECK_IN]);
        $checkOut2 = strtotime("+" . $booking2[BookingRequestContract::NIGHTS] . " days", $checkIn2);

        return ($checkIn1 < $checkOut2 && $checkOut1 > $checkIn2);
    }

    public static function removeOverlappedCombinations(BookingRequestList $bookingRequestList): array
    {
        $validCombinations = [[]];
        foreach ($bookingRequestList->bookingRequests() as $bookingRequest) {
            $newCombinations = [];
            foreach ($validCombinations as $combination) {
                $newCombination = $combination;
                $newCombination[] = $bookingRequest->toArray();
                $hasConflict = self::checkConflictExistingCombination($newCombination);

                if (!$hasConflict) {
                    $newCombinations[] = $newCombination;
                }
            }
            $validCombinations = array_merge($validCombinations, $newCombinations);
        }
        return $validCombinations;
    }

    private static function checkConflictExistingCombination(array $newCombination): bool
    {
        $hasConflict = false;
        foreach ($newCombination as $firstBooking) {
            foreach ($newCombination as $secondBooking) {
                if ($firstBooking[BookingRequestContract::REQUEST_ID] !== $secondBooking[BookingRequestContract::REQUEST_ID] && self::hasConflict($firstBooking, $secondBooking)) {
                    $hasConflict = true;
                    break 2;
                }
            }
        }
        return $hasConflict;
    }

    public static function findCombinationGreatestProfit(array $combinations): array
    {
        $bestCombination = [];
        $bestProfit = 0;
        $combinations = array_map('array_filter', $combinations);
        $combinations = array_filter($combinations);
        foreach ($combinations as $combination) {
            $combinationWithProfits = self::addTotalProfit($combination);
            if ($combinationWithProfits[BookingRequestContract::PROFIT] > $bestProfit) {
                $bestCombination = $combinationWithProfits;
                $bestProfit = $combinationWithProfits[BookingRequestContract::PROFIT];
            }
        }
        return $bestCombination;
    }

    public static function addTotalProfit(array $combination): array
    {
        $totalProfit = 0;
        if (!empty($combination)) {
            foreach ($combination as $booking) {
                $totalProfit += ($booking[BookingRequestContract::SELLING_RATE] * $booking[BookingRequestContract::MARGIN]) / 100;
            }
            $combination[BookingRequestContract::PROFIT] = isset($combination[BookingRequestContract::PROFIT]) ? $combination[BookingRequestContract::PROFIT] + $totalProfit : $totalProfit;
        }
        return $combination;
    }

    public function requestIds(): array
    {
        return $this->requestIds;
    }

    public function totalProfit(): float
    {
        return $this->totalProfit;
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