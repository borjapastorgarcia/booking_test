<?php

declare(strict_types=1);


namespace App\Tests\BookingRequest\Domain;


use App\Back\BookingRequest\Domain\BookingRequestCheckIn;
use App\Back\BookingRequest\Domain\BookingRequestContract;
use App\Back\BookingRequest\Domain\BookingRequestId;
use App\Back\BookingRequest\Domain\BookingRequestMargin;
use App\Back\BookingRequest\Domain\BookingRequestNumberOfNights;
use App\Back\BookingRequest\Domain\BookingRequestSellingRate;
use App\Back\BookingRequest\Domain\BookingRequest;
use App\Back\BookingRequest\Domain\ValidationErrorResponse;
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

    /* @test */
    public function test_check_booking_request_field_consistency()
    {
        $missingFieldsBookRequest = '[
        {
            "request_id" : "test",
            "check_in" : "2020-01-04",
            "nights" : 4,
            "selling_rate" : 156
        }]';

        $this->expectException(ValidationErrorResponse::class);

        BookingRequest::fromJson($missingFieldsBookRequest);
    }

    /* @test */
    public function test_check_booking_request_fields_consistency_ok()
    {
        $requiredFields = '[
        {
            "request_id" : "test",
            "check_in" : "2020-01-04",
            "nights" : 4,
            "selling_rate" : 156,
            "margin" : 156
        }]';

        BookingRequest::fromJson($requiredFields);

        $this->assertTrue(true);
    }
}