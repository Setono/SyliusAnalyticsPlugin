<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Provider;

use Setono\GoogleAnalyticsBundle\Provider\ContainerProviderInterface;
use Setono\GoogleAnalyticsBundle\ValueObject\Container;
use Setono\SyliusAnalyticsPlugin\Model\ContainerInterface;
use Setono\SyliusAnalyticsPlugin\Repository\ContainerRepositoryInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;

/**
 * This service overrides the default \Setono\GoogleAnalyticsBundle\Provider\ConfigurationBasedContainerProvider
 * to retrieve containers from the database instead of the configuration
 */
final class DatabaseContainerProvider implements ContainerProviderInterface
{
    private ChannelContextInterface $channelContext;

    private ContainerRepositoryInterface $containerRepository;

    public function __construct(
        ChannelContextInterface $channelContext,
        ContainerRepositoryInterface $containerRepository
    ) {
        $this->channelContext = $channelContext;
        $this->containerRepository = $containerRepository;
    }

    public function getContainers(): array
    {
        return array_values(array_map(
            static fn (ContainerInterface $container) => new Container((string) $container->getContainerId()),
            $this->containerRepository->findEnabledByChannel($this->channelContext->getChannel()),
        ));
    }
}
