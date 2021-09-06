<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener;

use Psr\EventDispatcher\EventDispatcherInterface;
use Setono\GoogleAnalyticsMeasurementProtocol\Hit\HitBuilder;
use Symfony\Bundle\SecurityBundle\Security\FirewallMap;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class PageviewSubscriber implements EventSubscriberInterface
{
    protected HitBuilder $pageviewHitBuilder;

    protected EventDispatcherInterface $eventDispatcher;

    private RequestStack $requestStack;

    private FirewallMap $firewallMap;

    public function __construct(
        HitBuilder $pageviewHitBuilder,
        EventDispatcherInterface $eventDispatcher,
        RequestStack $requestStack,
        FirewallMap $firewallMap
    ) {
        $this->pageviewHitBuilder = $pageviewHitBuilder;
        $this->eventDispatcher = $eventDispatcher;
        $this->requestStack = $requestStack;
        $this->firewallMap = $firewallMap;
    }

    protected function isShopContext(Request $request = null): bool
    {
        if (null === $request) {
            $request = $this->requestStack->getCurrentRequest();
            if (null === $request) {
                return true;
            }
        }

        $firewallConfig = $this->firewallMap->getFirewallConfig($request);
        if (null === $firewallConfig) {
            return true;
        }

        return $firewallConfig->getName() === 'shop';
    }

    protected static function formatAmount(int $amount): float
    {
        return round($amount / 100, 2);
    }
}
