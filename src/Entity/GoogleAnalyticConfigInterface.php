<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\ToggleableInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;

interface GoogleAnalyticConfigInterface extends
    ResourceInterface,
    ToggleableInterface,
    TranslatableInterface
{
    public function getId(): ?int;

    public function getTrackingId(): ?string;

    public function setTrackingId(string $trackingId): void;

    public function getName(): ?string;

    public function setName(string $name): void;
}
