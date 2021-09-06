<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener;

use Psr\EventDispatcher\EventDispatcherInterface;
use Setono\GoogleAnalyticsMeasurementProtocol\Hit\HitBuilder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

abstract class PageviewSubscriber implements EventSubscriberInterface
{
    protected HitBuilder $pageviewHitBuilder;

    protected EventDispatcherInterface $eventDispatcher;

    public function __construct(HitBuilder $pageviewHitBuilder, EventDispatcherInterface $eventDispatcher)
    {
        $this->pageviewHitBuilder = $pageviewHitBuilder;
        $this->eventDispatcher = $eventDispatcher;
    }
}
