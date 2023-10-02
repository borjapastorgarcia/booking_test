<?php

declare(strict_types=1);


namespace App\Tests\BookingRequest\Domain\ValueObjects;


use App\Back\BookingRequest\Domain\BookingRequestCheckIn;
use PHPUnit\Framework\TestCase;

final class BookingRequestCheckInTest extends TestCase
{
    /* @test */
    public function test_create_booking_request_check_in()
    {
        $checkInDate = '2023-10-10';
        $bookingRequestCheckIn = new BookingRequestCheckIn($checkInDate);
        $this->assertEquals($checkInDate, $bookingRequestCheckIn->value());
    }
}