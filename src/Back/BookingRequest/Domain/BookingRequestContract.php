<?php

declare(strict_types=1);

namespace App\Back\BookingRequest\Domain;

final class BookingRequestContract
{
    private const REQUEST_ID = 'request_id';
    private const CHECK_IN = 'check_in';
    private const NIGHTS = 'nights';
    private const SELLING_RATE = 'selling_rate';
    private const MARGIN = 'margin';
    private const PROFIT = 'profit';

    private const REQUIRED_JSON_FIELDS = [
        self::REQUEST_ID,
        self::CHECK_IN,
        self::NIGHTS,
        self::SELLING_RATE,
        self::MARGIN
    ];

    public static function request_id(): string
    {
        return self::REQUEST_ID;
    }

    public static function check_in(): string
    {
        return self::CHECK_IN;
    }

    public static function nights(): string
    {
        return self::NIGHTS;
    }

    public static function selling_rate(): string
    {
        return self::SELLING_RATE;
    }

    public static function margin(): string
    {
        return self::MARGIN;
    }

    public static function required_json_fields(): array
    {
        return self::REQUIRED_JSON_FIELDS;
    }

    public static function profit(): string
    {
        return self::PROFIT;
    }
}