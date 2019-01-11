<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Context;

use Setono\SyliusAnalyticsPlugin\Model\AnalyticsConfigInterface;
use Setono\SyliusAnalyticsPlugin\Repository\AnalyticsConfigRepositoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class AnalyticsConfigContext implements AnalyticsConfigContextInterface
{
    /** @var AnalyticsConfigRepositoryInterface */
    private $analyticConfigRepository;

    /** @var FactoryInterface */
    private $analyticConfigFactory;

    public function __construct(
        AnalyticsConfigRepositoryInterface $analyticConfigRepository,
        FactoryInterface $analyticConfigFactory
    ) {
        $this->analyticConfigRepository = $analyticConfigRepository;
        $this->analyticConfigFactory = $analyticConfigFactory;
    }

    public function getConfig(): AnalyticsConfigInterface
    {
        $config = $this->analyticConfigRepository->findConfig();
        if (null === $config) {
            /** @var AnalyticsConfigInterface $config */
            $config = $this->analyticConfigFactory->createNew();
            $config->setTrackingId(self::DEFAULT_CODE);
            $this->analyticConfigRepository->add($config);
        }

        return $config;
    }
}
