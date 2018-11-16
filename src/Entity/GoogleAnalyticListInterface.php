<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Entity;

use Sylius\Component\Channel\Model\ChannelsAwareInterface;
use Sylius\Component\Locale\Model\LocalesAwareInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

interface GoogleAnalyticListInterface extends ResourceInterface, ChannelsAwareInterface, LocalesAwareInterface
{
    public function getId(): ?int;
    public function getListId(): ?string;
    public function setListId($listId): void;
    public function getConfig(): ?GoogleAnalyticConfigInterface;
    public function setConfig(?GoogleAnalyticConfigInterface $config): void;
}
