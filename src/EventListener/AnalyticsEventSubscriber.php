<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener;

use Psr\EventDispatcher\EventDispatcherInterface;
use Setono\GoogleAnalyticsMeasurementProtocol\Hit\HitBuilder;
use Symfony\Bundle\SecurityBundle\Security\FirewallMap;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class AnalyticsEventSubscriber implements EventSubscriberInterface
{
    protected HitBuilder $hitBuilder;

    protected EventDispatcherInterface $eventDispatcher;

    private RequestStack $requestStack;

    private FirewallMap $firewallMap;

    public function __construct(
        HitBuilder $hitBuilder,
        EventDispatcherInterface $eventDispatcher,
        RequestStack $requestStack,
        FirewallMap $firewallMap
    ) {
        $this->hitBuilder = $hitBuilder;
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
}
