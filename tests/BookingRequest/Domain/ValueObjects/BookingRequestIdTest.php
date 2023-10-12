<?php

declare(strict_types=1);


namespace App\Tests\BookingRequest\Domain\ValueObjects;


use App\Back\BookingRequest\Domain\ValueObjects\BookingRequestId;
use PHPUnit\Framework\TestCase;

final class BookingRequestIdTest extends TestCase
{
    /* @test */
    public function test_create_booking_request_id()
    {
        $id = 'abc123';
        $bookingRequestId = new BookingRequestId($id);
        $this->assertEquals($id, $bookingRequestId->value());

    }
}