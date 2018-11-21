<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Entity;

use Sylius\Component\Resource\Model\ToggleableTrait;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Locale\Model\LocaleInterface;

class GoogleAnalyticConfig implements GoogleAnalyticConfigInterface
{
    use ToggleableTrait;

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
