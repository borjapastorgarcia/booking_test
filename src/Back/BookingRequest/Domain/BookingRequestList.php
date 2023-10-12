<?php

declare(strict_types=1);

namespace App\Back\BookingRequest\Domain;

use Doctrine\Common\Collections\ArrayCollection;

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

    public static function fromJson(string $bookingRequestsJson): BookingRequestList
    {
        $bookingRequestsArray = json_decode($bookingRequestsJson, true, 512, JSON_THROW_ON_ERROR);
        $bookingRequests = [];
        foreach ($bookingRequestsArray as $bookingRequestArray) {
            BookingRequest::checkBookingRequestFieldsConsistency($bookingRequestArray);
            $bookingRequests[] = BookingRequest::create(
                $bookingRequestArray[BookingRequestContract::REQUEST_ID],
                $bookingRequestArray[BookingRequestContract::CHECK_IN],
                $bookingRequestArray[BookingRequestContract::NIGHTS],
                $bookingRequestArray[BookingRequestContract::SELLING_RATE],
                $bookingRequestArray[BookingRequestContract::MARGIN],
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