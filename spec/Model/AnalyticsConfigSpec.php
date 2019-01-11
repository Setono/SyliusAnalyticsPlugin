<?php

declare(strict_types=1);

namespace spec\Setono\SyliusAnalyticsPlugin\Model;

use PhpSpec\ObjectBehavior;
use Setono\SyliusAnalyticsPlugin\Model\AnalyticsConfig;
use Setono\SyliusAnalyticsPlugin\Model\AnalyticsConfigInterface;

final class AnalyticsConfigSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(AnalyticsConfig::class);
    }

    function it_implements_google_analytic_config_interface(): void
    {
        $this->shouldHaveType(AnalyticsConfigInterface::class);
    }

    function it_has_no_id_by_default(): void
    {
        $this->getId()->shouldReturn(null);
    }

    function it_tracking_id_is_mutable(): void
    {
        $this->setTrackingId('ab12');
        $this->getTrackingId()->shouldReturn('ab12');
    }

    function it_toggles(): void
    {
        $this->enable();
        $this->isEnabled()->shouldReturn(true);
        $this->disable();
        $this->isEnabled()->shouldReturn(false);
    }
}
