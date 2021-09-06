<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener;

use Psr\EventDispatcher\EventDispatcherInterface;
use Setono\GoogleAnalyticsServerSideTrackingBundle\Factory\HitBuilderFactoryInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\OrderItemInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

abstract class UpdateCartSubscriber implements EventSubscriberInterface
{
    protected HitBuilderFactoryInterface $hitBuilderFactory;

    protected EventDispatcherInterface $eventDispatcher;

    public function __construct(
        HitBuilderFactoryInterface $hitBuilderFactory,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->hitBuilderFactory = $hitBuilderFactory;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function track(ResourceControllerEvent $resourceControllerEvent): void
    {
        $orderItem = $resourceControllerEvent->getSubject();

        if (!$orderItem instanceof OrderItemInterface) {
            return;
        }

        $this->_track($orderItem);
    }

    abstract protected function _track(OrderItemInterface $orderItem): void;
}
