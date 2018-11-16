<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Locale\Model\LocaleInterface;

class GoogleAnalyticList implements MailChimpListInterface
{
    /** @var int */
    protected $id;
    /** @var string */
    protected $listId;
    /** @var GoogleAnalyticConfigInterface */
    protected $config;
    /** @var GoogleAnalyticListInterface */
    protected $list;
    /** @var Collection|ChannelInterface[] */
    protected $channels;
    /** @var Collection|LocaleInterface[] */
    protected $locales;
    /** @var array */
    protected $emails = [];
    public function __construct()
    {
        $this->channels = new ArrayCollection();
        $this->locales = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getListId(): ?string
    {
        return $this->listId;
    }
    public function setListId($listId): void
    {
        $this->listId = $listId;
    }
    public function getConfig(): ?GoogleAnalyticConfigInterface
    {
        return $this->config;
    }
    public function setConfig(?GoogleAnalyticConfigInterface $config): void
    {
        $this->config = $config;
    }
    public function getChannels(): Collection
    {
        return $this->channels;
    }
    public function hasChannel(ChannelInterface $channel): bool
    {
        return $this->channels->contains($channel);
    }
    public function addChannel(ChannelInterface $channel): void
    {
        if (!$this->hasChannel($channel)) {
            $this->channels->add($channel);
        }
    }
    public function removeChannel(ChannelInterface $channel): void
    {
        if ($this->hasChannel($channel)) {
            $this->channels->removeElement($channel);
        }
    }
    public function getLocales(): Collection
    {
        return $this->locales;
    }
    public function addLocale(LocaleInterface $locale): void
    {
        if (!$this->hasLocale($locale)) {
            $this->locales->add($locale);
        }
    }
    public function removeLocale(LocaleInterface $locale): void
    {
        if ($this->hasLocale($locale)) {
            $this->locales->removeElement($locale);
        }
    }
    public function hasLocale(LocaleInterface $locale): bool
    {
        return $this->locales->contains($locale);
    }

}
