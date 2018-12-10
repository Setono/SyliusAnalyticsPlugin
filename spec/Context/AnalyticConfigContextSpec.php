<?php

declare(strict_types=1);

namespace spec\Setono\SyliusAnalyticsPlugin\Context;

use Setono\SyliusAnalyticsPlugin\Context\AnalyticConfigContext;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Setono\SyliusAnalyticsPlugin\Context\AnalyticConfigContextInterface;
use Setono\SyliusAnalyticsPlugin\Entity\GoogleAnalyticConfigInterface;
use Setono\SyliusAnalyticsPlugin\Repository\AnalyticConfigRepositoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class AnalyticConfigContextSpec extends ObjectBehavior
{
    function let(
        AnalyticConfigRepositoryInterface $analyticConfigRepository,
        FactoryInterface $analyticConfigFactory
    ): void {
        $this->beConstructedWith($analyticConfigRepository, $analyticConfigFactory);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(AnalyticConfigContext::class);
    }

    function it_implements_analytic_config_context_interface(): void
    {
        $this->shouldHaveType(AnalyticConfigContextInterface::class);
    }

    function it_gets_config(
        AnalyticConfigRepositoryInterface $analyticConfigRepository,
        GoogleAnalyticConfigInterface $config
    ): void {
        $analyticConfigRepository->findConfig()->willReturn($config);

        $this->getConfig();
    }

    function it_creates_new_config_when_config_is_null(
        AnalyticConfigRepositoryInterface $analyticConfigRepository,
        FactoryInterface $analyticConfigFactory,
        GoogleAnalyticConfigInterface $config
    ): void {
        $analyticConfigRepository->findConfig()->willReturn(null);
        $analyticConfigFactory->createNew()->willReturn($config);

        $config->setTrackingId('default')->shouldBeCalled();
        $analyticConfigRepository->add($config)->shouldBeCalled();

        $this->getConfig();
    }
}
