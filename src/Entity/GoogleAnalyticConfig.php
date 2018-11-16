<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Entity;

final class GoogleAnalyticConfig implements GoogleAnalyticConfigInterface
{
    /** @var int */
    protected $id;

    /** @var int */
    protected $trackingId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrackingId(): ?int
    {
        return $this->trackingId;
    }
}
