<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Repository;

use Setono\SyliusAnalyticsPlugin\Model\ContainerInterface;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface ContainerRepositoryInterface extends RepositoryInterface
{
    /**
     * Returns the containers that are enabled and enabled on the given channel
     *
     * @return array<array-key, ContainerInterface>
     */
    public function findEnabledByChannel(ChannelInterface $channel): array;
}
