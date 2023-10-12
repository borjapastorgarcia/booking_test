<?php

declare(strict_types=1);

namespace App\Back\BookingRequest\Domain\ValueObjects;

final class BookingRequestId
{
    public function __construct(private readonly string $id) { }

    public function value(): string
    {
        return $this->id;
    }
}