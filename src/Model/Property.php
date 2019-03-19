<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Channel\Model\ChannelInterface as BaseChannelInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Resource\Model\ToggleableTrait;

class Property implements PropertyInterface
{
    use ToggleableTrait;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $trackingId;

    /**
     * @var Collection|ChannelInterface[]
     */
    protected $channels;

    public function __construct()
    {
        $this->channels = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getTrackingId(): ?string
    {
        return $this->trackingId;
    }

    /**
     * {@inheritdoc}
     */
    public function setTrackingId(string $trackingId): void
    {
        $this->trackingId = $trackingId;
    }

    /**
     * {@inheritdoc}
     */
    public function getChannels(): Collection
    {
        return $this->channels;
    }

    /**
     * {@inheritdoc}
     */
    public function addChannel(BaseChannelInterface $channel): void
    {
        if (!$this->hasChannel($channel)) {
            $this->channels->add($channel);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeChannel(BaseChannelInterface $channel): void
    {
        if ($this->hasChannel($channel)) {
            $this->channels->removeElement($channel);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasChannel(BaseChannelInterface $channel): bool
    {
        return $this->channels->contains($channel);
    }
}
