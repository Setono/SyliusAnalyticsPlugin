<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Repository;

use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface PropertyRepositoryInterface extends RepositoryInterface
{
    /**
     * Returns the properties that are enabled and enabled on the given channel
     */
    public function findEnabledByChannel(ChannelInterface $channel): array;
}
