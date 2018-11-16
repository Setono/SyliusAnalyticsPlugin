<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;

interface GoogleAnalyticConfigInterface extends ResourceInterface
{
    public function getId(): ?int;
    public function getTrackingId(): ?string;
    public function setTrackingId(string $trackingId): void;
}
