<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener;

use Setono\SyliusAnalyticsPlugin\Tag\GtagTagInterface;
use Setono\SyliusAnalyticsPlugin\Tag\Tags;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;

final class RemoveFromCartSubscriber extends UpdateCartSubscriber
{
    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.order_item.post_delete' => [
                'track',
            ],
        ];
    }

    public function track(ResourceControllerEvent $event): void
    {
        $this->_track($event, Tags::TAG_REMOVE_FROM_CART, GtagTagInterface::EVENT_REMOVE_FROM_CART);
    }
}
