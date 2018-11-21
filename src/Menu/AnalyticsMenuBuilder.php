<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Menu;

use Setono\SyliusAnalyticsPlugin\Context\AnalyticConfigContextInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;


final class AnalyticsMenuBuilder
{
    /** @var AnalyticConfigContextInterface */
    private $analyticConfigContext;

    public function __construct(AnalyticConfigContextInterface $analyticConfigContext)
    {
        $this->analyticConfigContext = $analyticConfigContext;
    }

    public function addAnalytics(MenuBuilderEvent $event): void
    {
        $catalogMenu = $event->getMenu()->getChild('catalog');

        $catalogMenu
            ->addChild('analytics', [
                'route' => 'setono_sylius_analytics_plugin_admin_analytic_index',
                'routeParameters' => ['id' => $this->analyticConfigContext->getConfig()->getId()],
                ])
            ->setLabel('setono_sylius_analytics_plugin.ui.analytics_index')
            ->setLabelAttribute('icon', 'bullhorn')
        ;
    }
}
