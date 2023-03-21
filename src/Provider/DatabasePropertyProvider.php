<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Provider;

use Setono\GoogleAnalyticsBundle\Property\Property;
use Setono\GoogleAnalyticsBundle\Provider\PropertyProviderInterface;
use Setono\SyliusAnalyticsPlugin\Model\PropertyInterface;
use Setono\SyliusAnalyticsPlugin\Repository\PropertyRepositoryInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;

/**
 * This service overrides the default \Setono\GoogleAnalyticsBundle\Provider\ConfigurationBasedPropertyProvider
 * to retrieve properties from the database instead of the configuration
 */
final class DatabasePropertyProvider implements PropertyProviderInterface
{
    private ChannelContextInterface $channelContext;

    private PropertyRepositoryInterface $propertyRepository;

    public function __construct(
        ChannelContextInterface $channelContext,
        PropertyRepositoryInterface $propertyRepository,
    ) {
        $this->channelContext = $channelContext;
        $this->propertyRepository = $propertyRepository;
    }

    public function getProperties(): array
    {
        return array_values(array_map(
            static fn (PropertyInterface $property) => new Property((string) $property->getApiSecret(), (string) $property->getMeasurementId()),
            $this->propertyRepository->findEnabledByChannel($this->channelContext->getChannel()),
        ));
    }
}
