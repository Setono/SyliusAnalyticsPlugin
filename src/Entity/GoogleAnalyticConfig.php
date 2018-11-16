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

    /** @var Collection|GoogleAnalyticListInterface[] */
    protected $lists;

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

    public function getLists(): Collection
    {
        return $this->lists;
    }
    public function setLists($lists): void
    {
        $this->lists = $lists;
    }
    public function addList(GoogleAnalyticListInterface $googleAnalyticList): void
    {
        if (!$this->hasList($googleAnalyticList)) {
            $googleAnalyticList->setConfig($this);
            $this->lists->add($googleAnalyticList);
        }
    }
    public function removeList(GoogleAnalyticListInterface $googleAnalyticList): void
    {
        if ($this->hasList($googleAnalyticList)) {
            $googleAnalyticList->setConfig(null);
            $this->lists->removeElement($googleAnalyticList);
        }
    }
    public function hasList(GoogleAnalyticListInterface $googleAnalyticList): bool
    {
        return $this->lists->contains($googleAnalyticList);
    }
    public function getListForChannelAndLocale(ChannelInterface $channel, LocaleInterface $locale): ?GoogleAnalyticListInterface
    {
        /** @var GoogleAnalyticListInterface $list */
        foreach ($this->getLists() as $list) {
            if ($list->hasChannel($channel) && $list->hasLocale($locale)) {
                return $list;
            }
        }
        return null;
    }
}
