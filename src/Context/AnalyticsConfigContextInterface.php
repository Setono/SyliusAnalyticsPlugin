<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Context;

use Setono\SyliusAnalyticsPlugin\Model\AnalyticsConfigInterface;

interface AnalyticsConfigContextInterface
{
    public const DEFAULT_CODE = 'default';

    public function getConfig(): AnalyticsConfigInterface;
}
