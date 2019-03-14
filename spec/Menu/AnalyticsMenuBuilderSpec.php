<?php

declare(strict_types=1);

namespace spec\Setono\SyliusAnalyticsPlugin\Menu;

use Knp\Menu\ItemInterface;
use PhpSpec\ObjectBehavior;
use Setono\SyliusAnalyticsPlugin\Context\AnalyticsConfigContextInterface;
use Setono\SyliusAnalyticsPlugin\Menu\AnalyticsMenuBuilder;
use Setono\SyliusAnalyticsPlugin\Model\AnalyticsConfigInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AnalyticsMenuBuilderSpec extends ObjectBehavior
{
    function let(AnalyticsConfigContextInterface $analyticConfigContext): void
    {
        $this->beConstructedWith($analyticConfigContext);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AnalyticsMenuBuilder::class);
    }

    function it_adds_analytics(
        AnalyticsConfigContextInterface $analyticConfigContext,
        MenuBuilderEvent $event,
        ItemInterface $catalogMenu,
        ItemInterface $menu,
        AnalyticsConfigInterface $analyticConfig
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
