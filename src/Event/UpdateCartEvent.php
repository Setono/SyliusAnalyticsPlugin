<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Event;

use Setono\GoogleAnalyticsMeasurementProtocol\DTO\ProductData;
use Sylius\Component\Core\Model\OrderItemInterface;
use Webmozart\Assert\Assert;

/**
 * @psalm-consistent-constructor
 */
abstract class UpdateCartEvent extends AnalyticsEvent
{
    public ProductData $productData;

    public OrderItemInterface $orderItem;

    public function __construct(ProductData $productData, OrderItemInterface $product)
    {
        $this->productData = $productData;
        $this->orderItem = $product;
    }

    public static function createFromOrderItem(OrderItemInterface $orderItem, string $type = ProductData::TYPE_PRODUCT): self
    {
        $product = $orderItem->getProduct();
        Assert::notNull($product);

        $data = new ProductData((string) $product->getCode(), (string) $product->getName(), $type);
        $data->price = self::formatAmount($orderItem->getFullDiscountedUnitPrice());
        $data->quantity = $orderItem->getQuantity();

        return new static($data, $orderItem);
    }
}
