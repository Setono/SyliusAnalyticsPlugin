<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener;

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

        if (!$order instanceof OrderInterface) {
            return;
        }

        $channel = $order->getChannel();
        if (null === $channel) {
            return;
        }

        $data = [
            'transaction_id' => (string) $order->getNumber(),
            'affiliation' => $channel->getHostname() . ' (' . $order->getLocaleCode() . ')',
            'value' => (string) $this->formatMoney($order->getTotal()),
            'currency' => (string) $order->getCurrencyCode(),
            'tax' => (string) $this->formatMoney($order->getTaxTotal()),
            'shipping' => (string) $this->formatMoney($order->getShippingTotal()),
            'items' => [],
        ];

        foreach ($order->getItems() as $orderItem) {
            $variant = $orderItem->getVariant();
            if (null === $variant) {
                continue;
            }

            $dataItem = $this->createItem(
                $variant->getCode(),
                (string) $orderItem->getVariantName(),
                $orderItem->getQuantity(),
                $orderItem->getDiscountedUnitPrice()
            );

            $data['items'][] = $dataItem;
        }

        $this->tagBag->add(new GtagTag(
            GtagTagInterface::EVENT_PURCHASE,
            Tags::TAG_PURCHASE,
            $data
        ), TagBagInterface::SECTION_BODY_END);
    }
}
