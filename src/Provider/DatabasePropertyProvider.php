<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Provider;

use Setono\GoogleAnalyticsServerSideTrackingBundle\Provider\PropertyProviderInterface;
use Setono\SyliusAnalyticsPlugin\Model\PropertyInterface;
use Setono\SyliusAnalyticsPlugin\Repository\PropertyRepositoryInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;

final class DatabasePropertyProvider implements PropertyProviderInterface
{
    private ChannelContextInterface $channelContext;

    private PropertyRepositoryInterface $propertyRepository;

    public function __construct(ChannelContextInterface $channelContext, PropertyRepositoryInterface $propertyRepository)
    {
        $this->channelContext = $channelContext;
        $this->propertyRepository = $propertyRepository;
    }

    public function getProperties(): array
    {
        return array_map(
            static fn (PropertyInterface $property) => (string) $property->getTrackingId(),
            $this->propertyRepository->findEnabledByChannel($this->channelContext->getChannel()),
        );
    }
}
