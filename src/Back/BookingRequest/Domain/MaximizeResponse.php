<?php

declare(strict_types=1);

namespace App\Back\BookingRequest\Domain;

use App\Back\Shared\Domain\Bus\Query\Response;

final class MaximizeResponse implements Response
{
    public function __construct(
        private readonly array $requestIds,
        private readonly float $totalProfit,
        private readonly ?StatsResponse $statsData
    )
    {
    }

    public static function create(
        array          $requestIds,
        float          $totalProfit,
        ?StatsResponse $statsData = null
    ): self
    {
        return new self(
            $requestIds,
            $totalProfit,
            $statsData
        );
    }

    public static function generateMaximizeProfitResponse(BookingRequestList $bookingRequestList): self
    {
        $validBookingRequestCombinations = self::findCombinationGreatestProfit(self::removeOverlappedCombinations($bookingRequestList));
        $cleanedBookingsCombinations = self::cleanResponse([
            MaximizeResponseContract::REQUEST_IDS => array_column($validBookingRequestCombinations, BookingRequestContract::REQUEST_ID),
            MaximizeResponseContract::TOTAL_PROFIT => $validBookingRequestCombinations[BookingRequestContract::PROFIT],
            MaximizeResponseContract::STATS_DATA => $validBookingRequestCombinations
        ]);

        return self::create(
            $cleanedBookingsCombinations[MaximizeResponseContract::REQUEST_IDS],
            $cleanedBookingsCombinations[MaximizeResponseContract::TOTAL_PROFIT],
            StatsResponse::generateStats(
                BookingRequestList::fromJson(json_encode($cleanedBookingsCombinations[MaximizeResponseContract::STATS_DATA]))
            ));
    }


    public static function removeOverlappedCombinations(BookingRequestList $bookingRequestList): array
    {
        $validCombinations = [[]];
        foreach ($bookingRequestList->bookingRequests() as $bookingRequest) {
            $newCombinations = [];
            foreach ($validCombinations as $combination) {
                $newCombination = $combination;
                $newCombination[] = $bookingRequest->toArray();

                if (!self::hasCombinationConflict($newCombination)) {
                    $newCombinations[] = $newCombination;
                }
            }
            $validCombinations = array_merge($validCombinations, $newCombinations);
        }

        return array_filter(array_map('array_filter', $validCombinations));
    }

    public static function hasCombinationConflict(array $newCombination): bool
    {
        $hasConflict = false;
        foreach ($newCombination as $firstBooking) {
            foreach ($newCombination as $secondBooking) {
                if ($firstBooking[BookingRequestContract::REQUEST_ID] !== $secondBooking[BookingRequestContract::REQUEST_ID] && BookingRequest::hasConflictiveDates($firstBooking, $secondBooking)) {
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
                $totalProfit += BookingRequest::calculateProfit($booking[BookingRequestContract::SELLING_RATE], $booking[BookingRequestContract::MARGIN]);
            }
            $combination[BookingRequestContract::PROFIT] = isset($combination[BookingRequestContract::PROFIT]) ? $combination[BookingRequestContract::PROFIT] + $totalProfit : $totalProfit;
        }
        return $combination;
    }

    public function toArray(): array
    {
        return [
            MaximizeResponseContract::REQUEST_IDS => $this->requestIds(),
            MaximizeResponseContract::TOTAL_PROFIT => $this->totalProfit(),
            StatsResponseContract::AVG_NIGHT => $this->avgNight(),
            StatsResponseContract::MIN_NIGHT => $this->minNight(),
            StatsResponseContract::MAX_NIGHT => $this->maxNight()
        ];
    }

    private static function cleanResponse(array $maximizeResponse): array
    {
        if (isset($maximizeResponse[MaximizeResponseContract::STATS_DATA][BookingRequestContract::PROFIT])) {
            unset($maximizeResponse[MaximizeResponseContract::STATS_DATA][BookingRequestContract::PROFIT]);
        }
        return $maximizeResponse;
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
        return $this->statsData->avgNight();
    }

    public function minNight(): float
    {
        return $this->statsData->minNight();
    }

    public function maxNight(): float
    {
        return $this->statsData->maxNight();
    }

}