<?php

declare(strict_types=1);

namespace App\Tests\BookingRequest\Domain;

use App\Back\BookingRequest\Domain\BookingRequest;
use App\Back\BookingRequest\Domain\BookingRequestList;
use App\Back\BookingRequest\Domain\StatsResponse;
use App\Back\BookingRequest\Domain\StatsResponseContract;
use PHPUnit\Framework\TestCase;

final class StatsResponseTest extends TestCase
{
    /* @test */
    public function test_generate_stats()
    {
        $bookingRequestList = BookingRequestList::create([
            BookingRequest::create(
                "bookata_XY123",
                "2020-01-01",
                5,
                200,
                20
            ),
            BookingRequest::create(
                "kayete_PP234",
                "2020-01-04",
                4,
                156,
                5
            ),
            BookingRequest::create(
                "atropote_AA930",
                "2020-01-04",
                4,
                150,
                6
            ),
            BookingRequest::create(
                "acme_AAAAA",
                "2020-01-10",
                4,
                160,
                30
            )
        ]);
        $stats = StatsResponse::generateStats($bookingRequestList);
        $this->assertArrayHasKey(StatsResponseContract::AVG_NIGHT, $stats);
        $this->assertArrayHasKey(StatsResponseContract::MIN_NIGHT, $stats);
        $this->assertArrayHasKey(StatsResponseContract::MAX_NIGHT, $stats);
        $this->assertSame($stats[StatsResponseContract::AVG_NIGHT], 6.05);
    }

    /* @test */
    public function test_avg_profit_calculation()
    {
        $averageValue = 8.29;
        $averageProfit = StatsResponse::generateAverageProfit([8, 8.58], 2);
        $this->assertSame($averageProfit, $averageValue);

    }
}