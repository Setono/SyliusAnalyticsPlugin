<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Menu;

use Setono\SyliusAnalyticsPlugin\Context\AnalyticsConfigContextInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AnalyticsMenuBuilder
{
    /** @var AnalyticsConfigContextInterface */
    private $analyticsConfigContext;

    public function __construct(AnalyticsConfigContextInterface $analyticsConfigContext)
    {
        $this->analyticsConfigContext = $analyticsConfigContext;
    }

    public function addAnalytics(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu()->getChild('configuration');

        if($menu === null) {
            return;
        }

        $menu
            ->addChild('analytics', [
                'route' => 'setono_sylius_analytics_admin_google_analytics_config_update',
                'routeParameters' => ['id' => $this->analyticsConfigContext->getConfig()->getId()],
                ])
            ->setLabel('setono_sylius_analytics.ui.google_analytic_config_index')
            ->setLabelAttribute('icon', 'bullhorn')
        ;
    }
}
