<?php

declare(strict_types=1);

namespace App\Back\BookingRequest\Domain;

final class BookingRequestContract
{
    public const REQUEST_ID = 'request_id';
    public const CHECK_IN = 'check_in';
    public const NIGHTS = 'nights';
    public const SELLING_RATE = 'selling_rate';
    public const MARGIN = 'margin';
    public const PROFIT = 'profit';

    public const REQUIRED_JSON_FIELDS = [
        self::REQUEST_ID,
        self::CHECK_IN,
        self::NIGHTS,
        self::SELLING_RATE,
        self::MARGIN
    ];
}