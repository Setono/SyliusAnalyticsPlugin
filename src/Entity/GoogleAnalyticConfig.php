<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Entity;

final class GoogleAnalyticConfig implements GoogleAnalyticConfigInterface
{
    /** @var int */
    protected $id;

    /** @var string */
    protected $trackingId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrackingId(): ?string
    {
        return $this->trackingId;
    }

    public function setTrackingId(string $trackingId): void
    {
        $this->trackingId = $trackingId;
    }
}
