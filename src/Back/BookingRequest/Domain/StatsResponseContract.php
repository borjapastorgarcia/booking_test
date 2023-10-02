<?php

declare(strict_types=1);

namespace App\Back\BookingRequest\Domain;

final class StatsResponseContract
{
    public const AVG_NIGHT = 'avg_night';
    public const MIN_NIGHT = 'min_night';
    public const MAX_NIGHT = 'max_night';
}