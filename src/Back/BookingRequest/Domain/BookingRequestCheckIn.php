<?php

declare(strict_types=1);

namespace App\Back\BookingRequest\Domain;

final class BookingRequestCheckIn
{
    public function __construct(private readonly string $checkIn) { }

    public function value(): string
    {
        return $this->checkIn;
    }
}