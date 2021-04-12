<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener;

use Setono\SyliusAnalyticsPlugin\Event\AddToCartEvent;
use Sylius\Component\Core\Model\OrderItemInterface;

final class AddToCartSubscriber extends UpdateCartSubscriber
{
    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.order_item.post_add' => 'track',
        ];
    }

    public function _track(OrderItemInterface $orderItem): void
    {
        $event = AddToCartEvent::createFromOrderItem($orderItem);

        $this->eventDispatcher->dispatch($event);

        $this->hitBuilder->setProductAction('add');
        $event->productData->applyTo($this->hitBuilder);
    }
}
