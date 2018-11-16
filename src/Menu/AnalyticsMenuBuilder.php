<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AnalyticsMenuBuilder
{
    public function addAnalytics(MenuBuilderEvent $event): void
    {
        $catalogMenu = $event->getMenu()->getChild('catalog');
        $catalogMenu
            ->addChild('analytics', [
                'route' => 'setono_sylius_analytics_plugin_admin_analytic_index',
                'routeParameters' => ['id' => $this->mailChimpConfigContext->getConfig()->getId()],
                ])
            ->setLabel('setono_sylius_analytics_plugin.ui.analytics_index')
            ->setLabelAttribute('icon', 'bullhorn')
        ;
    }
}
