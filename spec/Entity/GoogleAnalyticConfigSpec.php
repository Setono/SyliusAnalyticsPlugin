<?php

declare(strict_types=1);

namespace spec\Setono\SyliusAnalyticsPlugin\Entity;

use Setono\SyliusAnalyticsPlugin\Entity\GoogleAnalyticConfig;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Setono\SyliusAnalyticsPlugin\Entity\GoogleAnalyticConfigInterface;


final class GoogleAnalyticConfigSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(GoogleAnalyticConfig::class);
    }

    function it_implements_google_analytic_config_interface(): void
    {
        $this->shouldHaveType(GoogleAnalyticConfigInterface::class);
    }

    function it_has_id(): void
    {
        $this->getId()->shouldReturn('1');
    }

    function it_has_name(): void
    {
        $this->setName('google');
        $this->getName()->shouldReturn('google');
    }

    function it_has_tracking_id(): void
    {
        $this->setTrackingId('ab12');
        $this->getTrackingId()->shouldReturn('ab12');
    }
}
