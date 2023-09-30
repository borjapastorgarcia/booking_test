<?php

declare(strict_types=1);


namespace App\Back\BookingRequest\Domain;


final class BookingRequestMargin
{
    public function __construct(private readonly int $value) { }

    public function value(): int
    {
        return $this->value;
    }
}