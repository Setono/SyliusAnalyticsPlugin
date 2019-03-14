<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Model;

use Sylius\Component\Resource\Model\ToggleableTrait;

class Property implements PropertyInterface
{
    use ToggleableTrait;

    /**
     * @var
     */
    protected $id;

    /**
     * @var string
     */
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
