<?php

declare(strict_types=1);

namespace App\Tests\BookingRequest\Domain;

use App\Back\BookingRequest\Domain\BookingRequest;
use App\Back\BookingRequest\Domain\BookingRequestContract;
use App\Back\BookingRequest\Domain\BookingRequestList;
use App\Back\BookingRequest\Domain\MaximizeResponse;
use PHPUnit\Framework\TestCase;

final class MaximizeResponseTest extends TestCase
{
    /* @test */
    public function test_check_conflicting_combination_with_conflict()
    {

        $newCombination = [
            [
                BookingRequestContract::REQUEST_ID => 'abc123',
                BookingRequestContract::CHECK_IN => '2023-10-10',
                BookingRequestContract::NIGHTS => 3,
            ],
            [
                BookingRequestContract::REQUEST_ID => 'def456',
                BookingRequestContract::CHECK_IN => '2023-10-11',
                BookingRequestContract::NIGHTS => 2,
            ],
        ];

        $result = MaximizeResponse::hasCombinationConflict($newCombination);

        $this->assertTrue($result);
    }

    /* @test */
    public function test_check_conflicting_combination_without_conflict()
    {

        $newCombination = [
            [
                BookingRequestContract::REQUEST_ID => 'test123',
                BookingRequestContract::CHECK_IN => '2023-10-10',
                BookingRequestContract::NIGHTS => 3,
            ],
            [
                BookingRequestContract::REQUEST_ID => 'qweerty',
                BookingRequestContract::CHECK_IN => '2023-10-14',
                BookingRequestContract::NIGHTS => 2,
            ],
        ];

        $result = MaximizeResponse::hasCombinationConflict($newCombination);

        $this->assertFalse($result);
    }

    /* @test */
    public function test_remove_overlapped_combinations()
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

        $combinations = MaximizeResponse::removeOverlappedCombinations($bookingRequestList);

        $this->assertIsArray($combinations);
        $this->assertCount(7, $combinations);
    }
}