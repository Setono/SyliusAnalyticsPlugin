<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener;

use Setono\SyliusAnalyticsPlugin\Builder\ItemBuilder;
use Setono\SyliusAnalyticsPlugin\Event\BuilderEvent;
use Setono\TagBag\Tag\GtagEvent;
use Setono\TagBag\Tag\GtagLibrary;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\OrderItemInterface;

abstract class UpdateCartSubscriber extends TagSubscriber
{
    protected function _track(ResourceControllerEvent $resourceControllerEvent, string $event): void
    {
        $orderItem = $resourceControllerEvent->getSubject();

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

        $this->tagBag->addTag(
            (new GtagEvent($event, $builder->getData()))
                ->addDependency(GtagLibrary::NAME)
        );
    }
}
