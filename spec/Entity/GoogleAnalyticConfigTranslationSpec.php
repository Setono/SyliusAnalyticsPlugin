<?php

declare(strict_types=1);

namespace spec\Setono\SyliusAnalyticsPlugin\Entity;

use Setono\SyliusAnalyticsPlugin\Entity\GoogleAnalyticConfigTranslation;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Setono\SyliusAnalyticsPlugin\Entity\GoogleAnalyticConfigTranslationInterface;


final class GoogleAnalyticConfigTranslationSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(GoogleAnalyticConfigTranslation::class);
    }

    function it_implements_google_analytic_config_translation_interface(): void
    {
        $this->shouldHaveType(GoogleAnalyticConfigTranslationInterface::class);
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
}
