<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Context;

use Setono\SyliusAnalyticsPlugin\Model\PropertyInterface;
use Setono\SyliusAnalyticsPlugin\Repository\PropertyRepositoryInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;

final class PropertyContext implements PropertyContextInterface
{
    /** @var ChannelContextInterface */
    private $channelContext;

    /** @var PropertyRepositoryInterface */
    private $propertyRepository;

    public function __construct(ChannelContextInterface $channelContext, PropertyRepositoryInterface $propertyRepository)
    {
        $this->channelContext = $channelContext;
        $this->propertyRepository = $propertyRepository;
    }

    /**
     * Returns the properties enabled for the active channel
     *
     * @return PropertyInterface[]
     */
    public function getProperties(): array
    {
        return $this->propertyRepository->findByChannel($this->channelContext->getChannel());
    }
}
