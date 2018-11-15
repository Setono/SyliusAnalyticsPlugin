<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Entity;

class GoogleAnalyticConfig
{
    /** @var int */
    protected $trackingId;

    public function getTrackingId(): ?int
    {
        return $this->trackingId;
    }
}
