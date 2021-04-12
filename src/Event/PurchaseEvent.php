<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Event;

use Setono\GoogleAnalyticsMeasurementProtocol\DTO\Event\PurchaseEventData;
use Setono\GoogleAnalyticsMeasurementProtocol\DTO\ProductData;
use Sylius\Component\Core\Model\OrderInterface;
use Webmozart\Assert\Assert;

final class PurchaseEvent extends AnalyticsEvent
{
    public PurchaseEventData $purchaseEventData;

    public OrderInterface $order;

    public function __construct(PurchaseEventData $purchase, OrderInterface $order)
    {
        $this->purchaseEventData = $purchase;
        $this->order = $order;
    }

    public static function createFromOrder(OrderInterface $order): self
    {
        $channel = $order->getChannel();
        Assert::notNull($channel);

        $items = [];
        foreach ($order->getItems() as $item) {
            $product = $item->getProduct();
            if (null === $product) {
                continue;
            }

            $productData = ProductData::createAsProductType((string) $product->getCode(), (string) $item->getProductName());
            $productData->quantity = $item->getQuantity();
            $productData->price = self::formatAmount($item->getFullDiscountedUnitPrice());

            $items[] = $productData;
        }

        $data = new PurchaseEventData(
            (string) $order->getNumber(),
            (string) $channel->getName() . ' (' . (string) $order->getLocaleCode() . ')',
            self::formatAmount($order->getTotal()),
            (string) $order->getCurrencyCode(),
            self::formatAmount($order->getTaxTotal()),
            self::formatAmount($order->getShippingTotal()),
            $items
        );

        return new self($data, $order);
    }
}
