<?php

declare(strict_types=1);

namespace App\Back\BookingRequest\Domain\ValueObjects;

final class BookingRequestSellingRate
{
    public function __construct(private readonly int $value) { }

    public function value(): int
    {
        return $this->value;
    }
}