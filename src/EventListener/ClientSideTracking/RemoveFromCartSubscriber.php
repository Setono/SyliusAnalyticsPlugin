<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener\ClientSideTracking;

use Setono\TagBag\Tag\GtagEventInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;

final class RemoveFromCartSubscriber extends UpdateCartSubscriber
{
    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.order_item.post_delete' => 'track',
        ];
    }

    public function track(ResourceControllerEvent $event): void
    {
        $this->_track($event, GtagEventInterface::EVENT_REMOVE_FROM_CART);
    }
}
