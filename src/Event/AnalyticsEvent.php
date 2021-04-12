<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Event;

abstract class AnalyticsEvent
{
    protected static function formatAmount(int $amount): float
    {
        return round($amount / 100, 2);
    }
}
