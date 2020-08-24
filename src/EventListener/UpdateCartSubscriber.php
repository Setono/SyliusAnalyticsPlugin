<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener;

use Setono\SyliusAnalyticsPlugin\Builder\ItemBuilder;
use Setono\SyliusAnalyticsPlugin\Event\BuilderEvent;
use Setono\SyliusAnalyticsPlugin\Tag\GtagTag;
use Setono\TagBagBundle\TagBag\TagBagInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\OrderItemInterface;

abstract class UpdateCartSubscriber extends TagSubscriber
{
    protected function _track(ResourceControllerEvent $event, string $key, string $action): void
    {
        $orderItem = $event->getSubject();

        if (!$orderItem instanceof OrderItemInterface || !$this->isShopContext()) {
            return;
        }

        $variant = $orderItem->getVariant();
        if (null === $variant) {
            return;
        }

        if (!$this->hasProperties()) {
            return;
        }

        $builder = ItemBuilder::create()
            ->setId((string) $variant->getCode())
            ->setName((string) $orderItem->getVariantName())
            ->setQuantity($orderItem->getQuantity())
            ->setPrice((float) $this->moneyFormatter->format($orderItem->getDiscountedUnitPrice()))
        ;

        $this->eventDispatcher->dispatch(new BuilderEvent($builder, $orderItem));

        $this->tagBag->add(new GtagTag(
            $key,
            $action,
            $builder
        ), TagBagInterface::SECTION_BODY_END);
    }
}
