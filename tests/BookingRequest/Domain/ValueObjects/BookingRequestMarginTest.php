<?php

declare(strict_types=1);


namespace App\Tests\BookingRequest\Domain\ValueObjects;


use App\Back\BookingRequest\Domain\BookingRequestMargin;
use PHPUnit\Framework\TestCase;

final class BookingRequestMarginTest extends TestCase
{
    /* @test */
    public function test_create_booking_request_margin()
    {
        $value = 100;
        $bookingRequestMargin = new BookingRequestMargin($value);
        $this->assertEquals($value, $bookingRequestMargin->value());
    }
}