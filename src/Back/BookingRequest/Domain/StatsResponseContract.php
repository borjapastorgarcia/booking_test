<?php

declare(strict_types=1);

namespace App\Back\BookingRequest\Domain;

final class StatsResponseContract
{
    private const AVG_NIGHT = 'avg_night';
    private const MIN_NIGHT = 'min_night';
    private const MAX_NIGHT = 'max_night';


    public static function avg_night(): string
    {
        return self::AVG_NIGHT;
    }

    public static function min_night(): string
    {
        return self::MIN_NIGHT;
    }

    public static function max_night(): string
    {
        return self::MAX_NIGHT;
    }
}