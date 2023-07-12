<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Channel\Model\ChannelInterface as BaseChannelInterface;
use Sylius\Component\Resource\Model\ToggleableTrait;

class Property implements PropertyInterface
{
    use ToggleableTrait;

    protected ?int $id = null;

    protected ?string $apiSecret = null;

    protected ?string $measurementId = null;

    /**
     * @var Collection|BaseChannelInterface[]
     *
     * @psalm-var Collection<array-key, BaseChannelInterface>
     */
    protected Collection $channels;

    public function __construct()
    {
        $this->channels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApiSecret(): ?string
    {
        return $this->apiSecret;
    }

    public function setApiSecret(?string $apiSecret): void
    {
        $this->apiSecret = $apiSecret;
    }

    public function getMeasurementId(): ?string
    {
        return $this->measurementId;
    }

    public function setMeasurementId(?string $measurementId): void
    {
        $this->measurementId = $measurementId;
    }

    public function getChannels(): Collection
    {
        return $this->channels;
    }

    public function addChannel(BaseChannelInterface $channel): void
    {
        if (!$this->hasChannel($channel)) {
            $this->channels->add($channel);
        }
    }

    public function removeChannel(BaseChannelInterface $channel): void
    {
        if ($this->hasChannel($channel)) {
            $this->channels->removeElement($channel);
        }
    }

    public function hasChannel(BaseChannelInterface $channel): bool
    {
        return $this->channels->contains($channel);
    }
}
