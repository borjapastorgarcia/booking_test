<?php

declare(strict_types=1);

namespace App\Tests\BookingRequest\Application\Stats\Generate;

use App\Back\BookingRequest\Application\Maximize\GenerateMaxCombinationProfit\GenerateMaximizeProfitBookingRequestQuery;
use App\Back\BookingRequest\Application\Maximize\GenerateMaxCombinationProfit\GenerateMaximizeProfitBookingRequestQueryHandler;
use App\Back\BookingRequest\Application\ValidationErrorResponse;
use App\Back\BookingRequest\Domain\MaximizeProfiitResponseContract;
use App\Back\BookingRequest\Domain\StatsResponseContract;
use JsonException;
use PHPUnit\Framework\TestCase;

final class GenerateMaximizeProfitBookingRequestQueryHandlerTest extends TestCase
{
    /* @test */
    public function test_maximize_response_is_ok()
    {
        //TODO build a data provider
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
        } catch (JsonException|ValidationErrorResponse $e) {
            die($e->getMessage());
        }

        $responseArray = $response->toArray();

        $this->assertArrayHasKey(MaximizeProfiitResponseContract::request_ids(), $responseArray);
        $this->assertArrayHasKey(MaximizeProfiitResponseContract::total_profit(), $responseArray);
        $this->assertArrayHasKey(StatsResponseContract::avg_night(), $responseArray);
        $this->assertArrayHasKey(StatsResponseContract::min_night(), $responseArray);
        $this->assertArrayHasKey(StatsResponseContract::max_night(), $responseArray);
    }

    private function buildQueryHandler(): GenerateMaximizeProfitBookingRequestQueryHandler
    {
        return new GenerateMaximizeProfitBookingRequestQueryHandler();
    }
}