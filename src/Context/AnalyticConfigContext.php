<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Context;

use Doctrine\ORM\EntityManagerInterface;
use Setono\SyliusAnalyticsPlugin\Entity\GoogleAnalyticConfig;
use Setono\SyliusAnalyticsPlugin\Entity\GoogleAnalyticConfigInterface;
use Setono\SyliusAnalyticsPlugin\Repository\AnalyticConfigRepositoryInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class AnalyticConfigContext
{
    /** @var AnalyticConfigRepositoryInterface */
    private $analyticConfigRepository;

    /** @var RepositoryInterface */
    private $analyticListRepository;

    /** @var ChannelContextInterface */
    private $channelContext;

    /** @var LocaleContextInterface */
    private $localeContext;

    /** @var FactoryInterface */
    private $analyticConfigFactory;

    /** @var FactoryInterface */
    private $analyticListFactory;

    /** @var EntityManagerInterface */
    private $configEntityManager;

    public function __construct(
        AnalyticConfigRepositoryInterface $analyticConfigRepository,
        RepositoryInterface $analyticListRepository,
        ChannelContextInterface $channelContext,
        LocaleContextInterface $localeContext,
        FactoryInterface $analyticConfigFactory,
        FactoryInterface $analyticListFactory,
        EntityManagerInterface $configEntityManager
    ) {
        $this->analyticConfigRepository = $analyticConfigRepository;
        $this->analyticListRepository = $analyticListRepository;
        $this->channelContext = $channelContext;
        $this->localeContext = $localeContext;
        $this->analyticConfigFactory = $analyticConfigFactory;
        $this->analyticListFactory = $analyticListFactory;
        $this->configEntityManager = $configEntityManager;
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
        $this->resolveDefaultLists($config);
        return $config;
    }

    private function resolveDefaultLists(GoogleAnalyticConfigInterface $config): void
    {
        /** @var ChannelInterface $channel */
        $channel = $this->channelContext->getChannel();
        $locale = $this->localeContext->getLocale();
        if (null === $config->getListForChannelAndLocale($channel, $locale)) {
            /** @var GoogleAnalyticConfigInterface $list */
            $list = $this->analyticListFactory->createNew();
            $list->setConfig($config);
            $list->addChannel($channel);
            $list->addLocale($locale);
            $config->addList($list);
            $list->setListId(uniqid($config->getCode()));
            $this->analyticListRepository->add($list);
            $this->configEntityManager->flush();
        }
    }

}
