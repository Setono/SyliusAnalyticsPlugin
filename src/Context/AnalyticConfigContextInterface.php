<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Context;

use Setono\SyliusAnalyticsPlugin\Model\GoogleAnalyticConfigInterface;

interface AnalyticConfigContextInterface
{
    public const DEFAULT_CODE = 'default';

    public function getConfig(): GoogleAnalyticConfigInterface;
}
