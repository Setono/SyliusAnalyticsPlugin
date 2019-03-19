<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener;

use Setono\SyliusAnalyticsPlugin\Tag\GtagTag;
use Setono\SyliusAnalyticsPlugin\Tag\GtagTagInterface;
use Setono\SyliusAnalyticsPlugin\Tag\Tags;
use Setono\TagBagBundle\TagBag\TagBagInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\OrderItemInterface;

final class AddToCartSubscriber extends TagSubscriber
{
    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.order_item.post_add' => [
                'add',
            ],
        ];
    }

    public function add(ResourceControllerEvent $event): void
    {
        $orderItem = $event->getSubject();

        if (!$orderItem instanceof OrderItemInterface) {
            return;
        }

        if(!$this->hasProperties()) {
            return;
        }

        $this->tagBag->add(new GtagTag(
            GtagTagInterface::EVENT_ADD_TO_CART,
            Tags::TAG_ADD_TO_CART,
            ['items' => $this->getOrderItemsArray($orderItem)]
        ), TagBagInterface::SECTION_BODY_END);
    }
}
