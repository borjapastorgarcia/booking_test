<?php

declare(strict_types=1);

namespace App\Tests\BookingRequest\Domain;

use App\Back\BookingRequest\Application\Stats\StatsResponse;
use App\Back\BookingRequest\Domain\BookingRequest;
use App\Back\BookingRequest\Domain\BookingRequestList;
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
        $stats = StatsResponse::generate($bookingRequestList);
        $statsArray = $stats->toArray();
        $this->assertArrayHasKey(StatsResponseContract::avg_night(), $statsArray);
        $this->assertArrayHasKey(StatsResponseContract::min_night(), $statsArray);
        $this->assertArrayHasKey(StatsResponseContract::max_night(), $statsArray);
        $this->assertSame($statsArray[StatsResponseContract::avg_night()], 6.05);
    }
}