<?php

declare(strict_types=1);

namespace App\Tests\BookingRequest\Domain;

use App\Back\BookingRequest\Application\Maximize\MaximizeProfitResponse;
use App\Back\BookingRequest\Domain\BookingRequestContract;
use App\Back\BookingRequest\Domain\BookingRequestList;
use PHPUnit\Framework\TestCase;

final class MaximizeProfitResponseTest extends TestCase
{
    /**
     * @dataProvider conflictingBookingCombinationDataProvider
     *
     * @test
     */
    public function test_check_conflicting_combination_with_conflict($newCombination, $expectedResult)
    {
        $result = MaximizeProfitResponse::hasCombinationConflict($newCombination);

        $this->assertEquals($result, $expectedResult);
    }

    /**
     * @dataProvider conflictingBookingCombinationDataProvider
     *
     * @test
     */
    public function test_check_conflicting_combination_without_conflict($newCombination, $expectedResult)
    {
        $result = MaximizeProfitResponse::hasCombinationConflict($newCombination);

        $this->assertEquals($result, $expectedResult);
    }

    /**
     * @dataProvider overlappedCombinationDataProvider
     *
     * @test
     */
    public function test_remove_overlapped_combinations($newCombination, $expectedResult)
    {
        $bookingRequestList = BookingRequestList::fromJson(json_encode($newCombination));

        $combinations = MaximizeProfitResponse::removeOverlappedCombinations($bookingRequestList);

        $this->assertIsArray($combinations);
        $this->assertCount($expectedResult, $combinations);
    }

    public function conflictingBookingCombinationDataProvider(): array
    {
        return [
            [
                [
                    [
                        BookingRequestContract::request_id() => 'abc123',
                        BookingRequestContract::check_in() => '2023-10-10',
                        BookingRequestContract::nights() => 3,
                    ],
                    [
                        BookingRequestContract::request_id() => 'def456',
                        BookingRequestContract::check_in() => '2023-10-11',
                        BookingRequestContract::nights() => 2,
                    ],
                ],
                true
            ],
            [
                [
                    [
                        BookingRequestContract::request_id() => 'abc123',
                        BookingRequestContract::check_in() => '2023-10-10',
                        BookingRequestContract::nights() => 3,
                    ],
                    [
                        BookingRequestContract::request_id() => 'def456',
                        BookingRequestContract::check_in() => '2023-10-14',
                        BookingRequestContract::nights() => 2,
                    ],
                ],
                false
            ],
        ];
    }

    public function overlappedCombinationDataProvider(): array
    {
        return [
            [
                [
                    [
                        BookingRequestContract::request_id() => 'bookata_XY123',
                        BookingRequestContract::check_in() => '2020-01-01',
                        BookingRequestContract::nights() => 5,
                        BookingRequestContract::margin() => 10,
                        BookingRequestContract::selling_rate() => 1
                    ],
                    [
                        BookingRequestContract::request_id() => 'kayete_PP234',
                        BookingRequestContract::check_in() => '2020-01-04',
                        BookingRequestContract::nights() => 4,
                        BookingRequestContract::margin() => 10,
                        BookingRequestContract::selling_rate() => 1
                    ],
                    [
                        BookingRequestContract::request_id() => 'atropote_AA930',
                        BookingRequestContract::check_in() => '2020-01-04',
                        BookingRequestContract::nights() => 4,
                        BookingRequestContract::margin() => 10,
                        BookingRequestContract::selling_rate() => 1
                    ],
                    [
                        BookingRequestContract::request_id() => 'acme_AAAAA',
                        BookingRequestContract::check_in() => '2020-01-10',
                        BookingRequestContract::nights() => 4,
                        BookingRequestContract::margin() => 10,
                        BookingRequestContract::selling_rate() => 1
                    ],
                ],
                7
            ]
        ];
    }
}