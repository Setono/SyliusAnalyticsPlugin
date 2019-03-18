<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Repository;

use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface PropertyRepositoryInterface extends RepositoryInterface
{
    /**
     * Returns the properties that enabled and enabled on the given channel
     *
     * @param ChannelInterface $channel
     *
     * @return array
     */
    public function findByChannel(ChannelInterface $channel): array;
}
