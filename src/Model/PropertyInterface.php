<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Model;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\ToggleableInterface;

interface PropertyInterface extends ResourceInterface, ToggleableInterface
{
    public function getId(): ?int;

    public function getTrackingId(): ?string;

    public function setTrackingId(string $trackingId): void;
}
