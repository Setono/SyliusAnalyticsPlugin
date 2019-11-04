<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener;

use Setono\SyliusAnalyticsPlugin\Builder\ItemBuilder;
use Setono\SyliusAnalyticsPlugin\Builder\PurchaseBuilder;
use Setono\SyliusAnalyticsPlugin\Event\BuilderEvent;
use Setono\SyliusAnalyticsPlugin\Tag\GtagTag;
use Setono\SyliusAnalyticsPlugin\Tag\GtagTagInterface;
use Setono\SyliusAnalyticsPlugin\Tag\Tags;
use Setono\TagBagBundle\TagBag\TagBagInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\OrderInterface;

final class PurchaseSubscriber extends TagSubscriber
{
    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.order.post_complete' => [
                'track',
            ],
        ];
    }

    public function track(ResourceControllerEvent $event): void
    {
        $order = $event->getSubject();

        if (!$order instanceof OrderInterface || !$this->isShopContext()) {
            return;
        }

        $channel = $order->getChannel();
        if (null === $channel) {
            return;
        }

        if (!$this->hasProperties()) {
            return;
        }

        $builder = PurchaseBuilder::create()
            ->setTransactionId($order->getNumber())
            ->setAffiliation($channel->getHostname() . ' (' . $order->getLocaleCode() . ')')
            ->setValue($this->moneyFormatter->format($order->getTotal()))
            ->setCurrency($order->getCurrencyCode())
            ->setTax($this->moneyFormatter->format($order->getTaxTotal()))
            ->setShipping($this->moneyFormatter->format($order->getShippingTotal()))
        ;

        foreach ($order->getItems() as $orderItem) {
            $variant = $orderItem->getVariant();
            if (null === $variant) {
                continue;
            }

            $itemBuilder = ItemBuilder::create()
                ->setId($variant->getCode())
                ->setName($orderItem->getVariantName())
                ->setQuantity($orderItem->getQuantity())
                ->setPrice($this->moneyFormatter->format($orderItem->getDiscountedUnitPrice()))
            ;

            $this->eventDispatcher->dispatch(new BuilderEvent($itemBuilder, $orderItem));

            $builder->addItem($itemBuilder);
        }

        $this->eventDispatcher->dispatch(new BuilderEvent($builder, $order));

        $this->tagBag->add(new GtagTag(
            Tags::TAG_PURCHASE,
            GtagTagInterface::EVENT_PURCHASE,
            $builder
        ), TagBagInterface::SECTION_BODY_END);
    }
}
