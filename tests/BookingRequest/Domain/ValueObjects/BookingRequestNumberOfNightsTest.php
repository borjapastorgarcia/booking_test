<?php

declare(strict_types=1);


namespace App\Tests\BookingRequest\Domain\ValueObjects;


use App\Back\BookingRequest\Domain\BookingRequestNumberOfNights;
use PHPUnit\Framework\TestCase;

final class BookingRequestNumberOfNightsTest extends TestCase
{
    /* @test */
    public function test_create_booking_request_number_of_nights()
    {
        $numberOfNights = 3;
        $bookingRequestNumberOfNights = new BookingRequestNumberOfNights($numberOfNights);
        $this->assertEquals($numberOfNights, $bookingRequestNumberOfNights->value());
    }
}