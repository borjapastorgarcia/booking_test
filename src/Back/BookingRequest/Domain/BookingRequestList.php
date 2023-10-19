<?php

declare(strict_types=1);

namespace App\Back\BookingRequest\Domain;

use App\Back\BookingRequest\Application\ValidationErrorResponse;
use Doctrine\Common\Collections\ArrayCollection;
use JsonException;

final class BookingRequestList
{
    private function __construct(private readonly array $bookingRequest) { }

    public static function create(array $bookingRequests): BookingRequestList
    {
        return new self($bookingRequests);
    }

    public function bookingRequests(): ArrayCollection
    {
        return new ArrayCollection($this->bookingRequest);
    }

    /**
     * @throws ValidationErrorResponse
     * @throws JsonException
     */
    public static function fromJson(string $bookingRequestsJson): BookingRequestList
    {
        $bookingRequestsArray = json_decode($bookingRequestsJson, true, 512, JSON_THROW_ON_ERROR);
        $bookingRequests = [];
        foreach ($bookingRequestsArray as $bookingRequestArray) {
            BookingRequest::checkBookingRequestFieldsConsistency($bookingRequestArray);
            $bookingRequests[] = BookingRequest::create(
                $bookingRequestArray[BookingRequestContract::request_id()],
                $bookingRequestArray[BookingRequestContract::check_in()],
                $bookingRequestArray[BookingRequestContract::nights()],
                $bookingRequestArray[BookingRequestContract::selling_rate()],
                $bookingRequestArray[BookingRequestContract::margin()],
            );
        }
        return new self($bookingRequests);
    }

    public static function generateAverageProfit(array $profitPerNight, int $bookingRequestListSize): int|float
    {
        return array_reduce($profitPerNight, function ($sum, $object) {
                return $sum += $object;
            }) / $bookingRequestListSize;
    }

    public function getUnitBookValues(): array
    {
        $profitsPerNight = [];
        /* @var BookingRequest $bookingRequest */
        foreach ($this->bookingRequests() as $bookingRequest) {
            $profitsPerNight [] = $bookingRequest->profitPerNight();
        }
        return $profitsPerNight;
    }
}