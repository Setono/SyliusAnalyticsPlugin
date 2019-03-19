<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener;

use Setono\SyliusAnalyticsPlugin\Tag\GtagTag;
use Setono\SyliusAnalyticsPlugin\Tag\GtagTagInterface;
use Setono\SyliusAnalyticsPlugin\Tag\Tags;
use Setono\TagBagBundle\TagBag\TagBagInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\OrderItemInterface;

final class RemoveFromCartSubscriber extends TagSubscriber
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
        $orderItem = $event->getSubject();

        if (!$orderItem instanceof OrderItemInterface) {
            return;
        }

        $this->tagBag->add(new GtagTag(
            GtagTagInterface::EVENT_REMOVE_FROM_CART,
            Tags::TAG_REMOVE_FROM_CART,
            ['items' => $this->getOrderItemsArray($orderItem)]
        ), TagBagInterface::SECTION_BODY_END);
    }
}
