<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Context;

use Setono\SyliusAnalyticsPlugin\Model\GoogleAnalyticConfigInterface;
use Setono\SyliusAnalyticsPlugin\Repository\GoogleAnalyticConfigRepositoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class AnalyticConfigContext implements AnalyticConfigContextInterface
{
    /** @var GoogleAnalyticConfigRepositoryInterface */
    private $analyticConfigRepository;

    /** @var FactoryInterface */
    private $analyticConfigFactory;

    public function __construct(
        GoogleAnalyticConfigRepositoryInterface $analyticConfigRepository,
        FactoryInterface $analyticConfigFactory
    ) {
        $this->analyticConfigRepository = $analyticConfigRepository;
        $this->analyticConfigFactory = $analyticConfigFactory;
    }

    public function getConfig(): GoogleAnalyticConfigInterface
    {
        $config = $this->analyticConfigRepository->findConfig();
        if (null === $config) {
            /** @var GoogleAnalyticConfigInterface $config */
            $config = $this->analyticConfigFactory->createNew();
            $config->setTrackingId(self::DEFAULT_CODE);
            $this->analyticConfigRepository->add($config);
        }

        return $config;
    }
}
