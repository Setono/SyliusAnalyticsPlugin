<?php

declare(strict_types=1);

namespace spec\Setono\SyliusAnalyticsPlugin\Menu;

use Setono\SyliusAnalyticsPlugin\Context\AnalyticConfigContextInterface;
use Setono\SyliusAnalyticsPlugin\Menu\AnalyticsMenuBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AnalyticsMenuBuilderSpec extends ObjectBehavior
{
    function let(AnalyticConfigContextInterface $analyticConfigContext): void
    {
        $this->beConstructedWith($analyticConfigContext);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AnalyticsMenuBuilder::class);
    }

    function it_adds_analytics(
        AnalyticConfigContextInterface $analyticConfigContext,
        MenuBuilderEvent $event,
        MenuBuilderEvent $catalogMenu
    ): void {
        $event->getMenu()->getChild('catalog')->willReturn($catalogMenu);

        $catalogMenu
            ->addChild('analytics', [
                'route' => 'setono_sylius_analytics_plugin_admin_analytic_update',
                'routeParameters' => ['id' => $analyticConfigContext->getConfig()->getId()],
            ])
            ->willReturn($catalogMenu)
        ;
        $catalogMenu->setLabel('setono_sylius_analytics_plugin.ui.analytics_index')->willReturn($catalogMenu);
        $catalogMenu->setLabelAttribute('icon', 'bullhorn')->shouldBeCalled();
    }
}
