<?php

declare(strict_types=1);

namespace App\Tests\BookingRequest\Domain\ValueObjects;

use App\Back\BookingRequest\Domain\BookingRequestList;
use App\Back\BookingRequest\Domain\ValidationErrorResponse;
use PHPUnit\Framework\TestCase;

final class BookingRequestListsTest extends TestCase
{
    /* @test */
    public function test_avg_profit_calculation()
    {
        $averageValue = 8.29;
        $averageProfit = BookingRequestList::generateAverageProfit([8, 8.58], 2);
        $this->assertSame($averageProfit, $averageValue);

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

        BookingRequestList::fromJson($missingFieldsBookRequest);
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

        BookingRequestList::fromJson($requiredFields);

        $this->assertTrue(true);
    }
}