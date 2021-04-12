<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener;

use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\OrderItemInterface;

abstract class UpdateCartSubscriber extends AnalyticsEventSubscriber
{
    public function track(ResourceControllerEvent $resourceControllerEvent): void
    {
        $orderItem = $resourceControllerEvent->getSubject();

        if (!$orderItem instanceof OrderItemInterface || !$this->isShopContext()) {
            return;
        }

        $variant = $orderItem->getVariant();
        if (null === $variant) {
            return;
        }

        $this->_track($orderItem);
    }

    abstract protected function _track(OrderItemInterface $orderItem): void;
}
