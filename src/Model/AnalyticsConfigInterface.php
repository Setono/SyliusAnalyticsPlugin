<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Model;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\ToggleableInterface;

interface AnalyticsConfigInterface extends ResourceInterface, ToggleableInterface
{
    /**
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * @return string|null
     */
    public function getTrackingId(): ?string;

    /**
     * @param string $trackingId
     */
    public function setTrackingId(string $trackingId): void;
}
