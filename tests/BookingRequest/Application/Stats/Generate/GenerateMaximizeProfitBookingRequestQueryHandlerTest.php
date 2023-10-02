<?php

declare(strict_types=1);

namespace App\Tests\BookingRequest\Application\Stats\Generate;

use App\Back\BookingRequest\Application\Maximize\GenerateMaxCombinationProfit\GenerateMaximizeProfitBookingRequestQuery;
use App\Back\BookingRequest\Application\Maximize\GenerateMaxCombinationProfit\GenerateMaximizeProfitBookingRequestQueryHandler;
use App\Back\BookingRequest\Domain\MaximizeResponseContract;
use App\Back\BookingRequest\Domain\StatsResponseContract;
use PHPUnit\Framework\TestCase;

final class GenerateMaximizeProfitBookingRequestQueryHandlerTest extends TestCase
{
    /* @test */
    public function test_maximize_response_is_ok()
    {
        $testData = '[
        {
            "request_id": "A",
            "check_in": "2018-01-01",
            "nights": 10,
            "selling_rate": 1000,
            "margin": 10
        },
        {
            "request_id": "B",
            "check_in": "2018-01-06",
            "nights": 10,
            "selling_rate": 700,
            "margin": 10
        },
        {
            "request_id": "C",
            "check_in": "2018-01-12",
            "nights": 10,
            "selling_rate": 400,
            "margin": 10
        }
        ]';

        try {
            $response = $this->buildQueryHandler()(new GenerateMaximizeProfitBookingRequestQuery(
                $testData
            ));
        } catch (\JsonException $e) {
            die($e->getMessage());
        }

        $this->assertArrayHasKey(MaximizeResponseContract::REQUEST_IDS, $response->toArray());
        $this->assertArrayHasKey(MaximizeResponseContract::TOTAL_PROFIT, $response->toArray());
        $this->assertArrayHasKey(StatsResponseContract::AVG_NIGHT, $response->toArray());
        $this->assertArrayHasKey(StatsResponseContract::MIN_NIGHT, $response->toArray());
        $this->assertArrayHasKey(StatsResponseContract::MAX_NIGHT, $response->toArray());
    }

    private function buildQueryHandler(): GenerateMaximizeProfitBookingRequestQueryHandler
    {
        return new GenerateMaximizeProfitBookingRequestQueryHandler();
    }
}