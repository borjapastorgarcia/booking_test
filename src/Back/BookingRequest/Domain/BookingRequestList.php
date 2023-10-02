<?php

declare(strict_types=1);

namespace App\Back\BookingRequest\Domain;

use Doctrine\Common\Collections\ArrayCollection;

final class BookingRequestList
{
    private function __construct(private readonly array $bookingRequest) { }

    public function bookingRequests(): ArrayCollection
    {
        return new ArrayCollection($this->bookingRequest);
    }

    public static function create(array $bookingRequest): BookingRequestList
    {
        return new self($bookingRequest);
    }
}