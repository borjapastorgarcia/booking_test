<?php

declare(strict_types=1);

namespace App\Back\BookingRequest\Domain;

final class MaximizeProfiitResponseContract
{
    private const REQUEST_IDS   = 'request_ids';
    private const TOTAL_PROFIT  = 'total_profit';
    private const STATS_DATA    = 'stats_data';

    public static function request_ids(): string
    {
        return self::REQUEST_IDS;
    }

    public static function total_profit(): string
    {
        return self::TOTAL_PROFIT;
    }

    public static function stats_data(): string
    {
        return self::STATS_DATA;
    }

}