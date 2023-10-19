<?php

declare(strict_types=1);

namespace App\Back\BookingRequest\Application\Maximize;

use App\Back\BookingRequest\Application\Stats\StatsResponse;
use App\Back\BookingRequest\Application\ValidationErrorResponse;
use App\Back\BookingRequest\Domain\BookingRequest;
use App\Back\BookingRequest\Domain\BookingRequestContract;
use App\Back\BookingRequest\Domain\BookingRequestList;
use App\Back\BookingRequest\Domain\MaximizeProfiitResponseContract;
use App\Back\BookingRequest\Domain\StatsResponseContract;
use App\Back\Shared\Domain\Bus\Query\Response;
use JsonException;

final class MaximizeProfitResponse implements Response
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

    /**
     * @throws ValidationErrorResponse
     * @throws JsonException
     */
    public static function generate(BookingRequestList $bookingRequestList): self
    {
        $validBookingRequestCombinations = self::findCombinationGreatestProfit(self::removeOverlappedCombinations($bookingRequestList));
        $cleanedBookingsCombinations = self::cleanResponse([
            MaximizeProfiitResponseContract::request_ids() => array_column($validBookingRequestCombinations, BookingRequestContract::request_id()),
            MaximizeProfiitResponseContract::total_profit() => $validBookingRequestCombinations[BookingRequestContract::profit()],
            MaximizeProfiitResponseContract::stats_data() => $validBookingRequestCombinations
        ]);

        return self::create(
            $cleanedBookingsCombinations[MaximizeProfiitResponseContract::request_ids()],
            $cleanedBookingsCombinations[MaximizeProfiitResponseContract::total_profit()],
            StatsResponse::generate(
                BookingRequestList::fromJson(json_encode($cleanedBookingsCombinations[MaximizeProfiitResponseContract::stats_data()]))
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
                if (BookingRequest::hasDifferentIds($firstBooking, $secondBooking) && BookingRequest::hasConflictiveDates($firstBooking, $secondBooking)) {
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
            if ($combinationWithProfits[BookingRequestContract::profit()] > $bestProfit) {
                $bestCombination = $combinationWithProfits;
                $bestProfit = $combinationWithProfits[BookingRequestContract::profit()];
            }
        }
        return $bestCombination;
    }

    public static function addTotalProfit(array $combination): array
    {
        $totalProfit = 0;
        if (!empty($combination)) {
            foreach ($combination as $booking) {
                $totalProfit += BookingRequest::calculateProfit(
                    $booking[BookingRequestContract::selling_rate()],
                    $booking[BookingRequestContract::margin()]
                );
            }
            $combination[BookingRequestContract::profit()] = isset($combination[BookingRequestContract::profit()]) ? $combination[BookingRequestContract::profit()] + $totalProfit : $totalProfit;
        }
        return $combination;
    }

    public function toArray(): array
    {
        return [
            MaximizeProfiitResponseContract::request_ids()     => $this->requestIds(),
            MaximizeProfiitResponseContract::total_profit()    => $this->totalProfit(),
            StatsResponseContract::avg_night()          => $this->avgNight(),
            StatsResponseContract::min_night()          => $this->minNight(),
            StatsResponseContract::max_night()          => $this->maxNight()
        ];
    }

    private static function cleanResponse(array $maximizeResponse): array
    {
        if (isset($maximizeResponse[MaximizeProfiitResponseContract::stats_data()][BookingRequestContract::profit()])) {
            unset($maximizeResponse[MaximizeProfiitResponseContract::stats_data()][BookingRequestContract::profit()]);
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