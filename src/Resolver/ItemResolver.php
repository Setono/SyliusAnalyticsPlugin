<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Resolver;

use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\Item\Item;
use Setono\SyliusAnalyticsPlugin\Util\FormatAmountTrait;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Product\Model\ProductOptionValueInterface;
use Webmozart\Assert\Assert;

final class ItemResolver implements ItemResolverInterface
{
    use FormatAmountTrait;

    public function resolveFromOrderItem(OrderItemInterface $orderItem): Item
    {
        $product = $orderItem->getProduct();
        Assert::notNull($product);

        $variant = $orderItem->getVariant();
        Assert::notNull($variant);

        $variantStr = implode(
            '-',
            $variant
                ->getOptionValues()
                ->map(static fn (ProductOptionValueInterface $productOptionValue) => $productOptionValue->getValue())
                ->toArray(),
        );

        return Item::create()
            ->setId($product->getCode())
            ->setName($orderItem->getProductName())
            ->setVariant($variantStr)
            ->setQuantity($orderItem->getQuantity())
            ->setPrice(self::formatAmount($orderItem->getFullDiscountedUnitPrice()))
        ;
    }
}
