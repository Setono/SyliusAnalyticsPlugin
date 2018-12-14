<?php

declare(strict_types=1);

namespace spec\Setono\SyliusAnalyticsPlugin\Menu;

use Knp\Menu\ItemInterface;
use Setono\SyliusAnalyticsPlugin\Context\AnalyticConfigContextInterface;
use Setono\SyliusAnalyticsPlugin\Model\GoogleAnalyticConfigInterface;
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
        ItemInterface $catalogMenu,
        ItemInterface $menu,
        GoogleAnalyticConfigInterface $analyticConfig
    ): void {
        $analyticConfig->getId()->willReturn(1);
        $analyticConfigContext->getConfig()->willReturn($analyticConfig);
        $event->getMenu()->willReturn($menu);
        $menu->getChild('catalog')->willReturn($catalogMenu);

        $catalogMenu
            ->addChild('analytics', [
                'route' => 'setono_sylius_analytics_admin_google_analytics_config_update',
                'routeParameters' => ['id' => 1],
            ])
            ->willReturn($catalogMenu)
        ;
        $catalogMenu->setLabel('setono_sylius_analytics.ui.google_analytic_config_index')->willReturn($catalogMenu);
        $catalogMenu->setLabelAttribute('icon', 'bullhorn')->shouldBeCalled();

        $this->addAnalytics($event);
    }
}
