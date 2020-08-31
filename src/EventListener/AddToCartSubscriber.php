<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener;

use Setono\TagBag\Tag\GtagEventInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;

final class AddToCartSubscriber extends UpdateCartSubscriber
{
    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.order_item.post_add' => 'track',
        ];
    }

    public function track(ResourceControllerEvent $event): void
    {
        $this->_track($event, GtagEventInterface::EVENT_ADD_TO_CART);
    }
}
