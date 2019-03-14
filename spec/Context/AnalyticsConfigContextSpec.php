<?php

declare(strict_types=1);

namespace spec\Setono\SyliusAnalyticsPlugin\Context;

use PhpSpec\ObjectBehavior;
use Setono\SyliusAnalyticsPlugin\Context\AnalyticsConfigContext;
use Setono\SyliusAnalyticsPlugin\Context\AnalyticsConfigContextInterface;
use Setono\SyliusAnalyticsPlugin\Model\AnalyticsConfigInterface;
use Setono\SyliusAnalyticsPlugin\Repository\AnalyticsConfigRepositoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class AnalyticsConfigContextSpec extends ObjectBehavior
{
    function let(
        AnalyticsConfigRepositoryInterface $analyticConfigRepository,
        FactoryInterface $analyticConfigFactory
    ): void {
        $this->beConstructedWith($analyticConfigRepository, $analyticConfigFactory);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(AnalyticsConfigContext::class);
    }

    function it_implements_analytic_config_context_interface(): void
    {
        $this->shouldHaveType(AnalyticsConfigContextInterface::class);
    }

    function it_gets_config(
        AnalyticsConfigRepositoryInterface $analyticConfigRepository,
        AnalyticsConfigInterface $config
    ): void {
        $analyticConfigRepository->findConfig()->willReturn($config);

        $this->getConfig();
    }

    function it_creates_new_config_when_config_is_null(
        AnalyticsConfigRepositoryInterface $analyticConfigRepository,
        FactoryInterface $analyticConfigFactory,
        AnalyticsConfigInterface $config
    ): void {
        $analyticConfigRepository->findConfig()->willReturn(null);
        $analyticConfigFactory->createNew()->willReturn($config);

        $config->setTrackingId('default')->shouldBeCalled();
        $analyticConfigRepository->add($config)->shouldBeCalled();

        $this->getConfig();
    }
}
