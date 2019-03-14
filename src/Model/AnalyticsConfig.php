<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Model;

use Sylius\Component\Resource\Model\ToggleableTrait;

class AnalyticsConfig implements AnalyticsConfigInterface
{
    use ToggleableTrait;

    /** @var int */
    protected $id;

    /** @var string|null */
    protected $trackingId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrackingId(): ?string
    {
        return $this->trackingId;
    }

    public function setTrackingId(?string $trackingId): void
    {
        $this->trackingId = $trackingId;
    }
}
