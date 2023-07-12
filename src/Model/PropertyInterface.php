<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Model;

use Sylius\Component\Channel\Model\ChannelsAwareInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\ToggleableInterface;

interface PropertyInterface extends ResourceInterface, ToggleableInterface, ChannelsAwareInterface
{
    public function getId(): ?int;

    public function getApiSecret(): ?string;

    public function setApiSecret(?string $apiSecret): void;

    public function getMeasurementId(): ?string;

    public function setMeasurementId(?string $measurementId): void;
}
