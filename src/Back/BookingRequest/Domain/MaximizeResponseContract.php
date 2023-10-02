<?php

declare(strict_types=1);

namespace App\Back\BookingRequest\Domain;

final class MaximizeResponseContract
{
    public const REQUEST_IDS = 'request_ids';
    public const TOTAL_PROFIT = 'total_profit';
    public const STATS_DATA = 'stats_data';
}