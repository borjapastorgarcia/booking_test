<?php

declare(strict_types=1);


namespace App\Tests\BookingRequest\Domain\ValueObjects;


use App\Back\BookingRequest\Domain\ValueObjects\BookingRequestSellingRate;
use PHPUnit\Framework\TestCase;

final class BookingRequestSellingRateTest extends TestCase
{
    /* @test */
    public function test_create_booking_request_selling_rate()
    {
        $sellingRateValue = 500;
        $bookingRequestSellingRate = new BookingRequestSellingRate($sellingRateValue);
        $this->assertEquals($sellingRateValue, $bookingRequestSellingRate->value());
    }
}