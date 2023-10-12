<?php

declare(strict_types=1);


namespace App\Tests\BookingRequest\Domain;


use App\Back\BookingRequest\Domain\BookingRequest;
use App\Back\BookingRequest\Domain\ValueObjects\BookingRequestCheckIn;
use App\Back\BookingRequest\Domain\ValueObjects\BookingRequestId;
use App\Back\BookingRequest\Domain\ValueObjects\BookingRequestMargin;
use App\Back\BookingRequest\Domain\ValueObjects\BookingRequestNumberOfNights;
use App\Back\BookingRequest\Domain\ValueObjects\BookingRequestSellingRate;
use PHPUnit\Framework\TestCase;

final class BookingRequestTest extends TestCase
{
    /* @test */
    public function test_create_booking_request()
    {
        $idValue = 'asdfqwerty';
        $checkInValue = '2023-10-10';
        $nightsValue = 3;
        $sellingRateValue = 500;
        $marginValue = 50;
        $bookingRequestId = new BookingRequestId($idValue);
        $checkIn = new BookingRequestCheckIn($checkInValue);
        $nights = new BookingRequestNumberOfNights($nightsValue);
        $sellingRate = new BookingRequestSellingRate($sellingRateValue);
        $margin = new BookingRequestMargin($marginValue);

        $bookingRequest = BookingRequest::create(
            $idValue,
            $checkInValue,
            $nightsValue,
            $sellingRateValue,
            $marginValue
        );


        $this->assertEquals($bookingRequestId->value(), $bookingRequest->id());
        $this->assertEquals($checkIn->value(), $bookingRequest->checkIn());
        $this->assertEquals($nights->value(), $bookingRequest->nights());
        $this->assertEquals($sellingRate->value(), $bookingRequest->sellingRate());
        $this->assertEquals($margin->value(), $bookingRequest->margin());
    }
}