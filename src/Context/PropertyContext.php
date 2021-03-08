<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Context;

use Setono\SyliusAnalyticsPlugin\Model\PropertyInterface;
use Setono\SyliusAnalyticsPlugin\Repository\PropertyRepositoryInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;

final class PropertyContext implements PropertyContextInterface
{
    /**
     * The properties are cached in this property
     *
     * @var array<array-key, PropertyInterface>
     */
    private ?array $properties = null;

    private ChannelContextInterface $channelContext;

    private PropertyRepositoryInterface $propertyRepository;

    public function __construct(ChannelContextInterface $channelContext, PropertyRepositoryInterface $propertyRepository)
    {
        $this->channelContext = $channelContext;
        $this->propertyRepository = $propertyRepository;
    }

    /**
     * Returns the properties enabled for the active channel
     *
     * @return array<array-key, PropertyInterface>
     */
    public function getProperties(): array
    {
        if (null === $this->properties) {
            $this->properties = $this->propertyRepository->findEnabledByChannel($this->channelContext->getChannel());
        }

        return $this->properties;
    }
}
