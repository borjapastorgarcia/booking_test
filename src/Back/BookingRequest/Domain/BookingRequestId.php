<?php

declare(strict_types=1);


namespace App\Back\BookingRequest\Domain;


final class BookingRequestId
{
    public function __construct(private readonly string $id) { }
}