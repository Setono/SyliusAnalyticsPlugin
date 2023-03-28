<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Resolver;

use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\Item\Item;
use Setono\SyliusAnalyticsPlugin\Util\FormatAmountTrait;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Calculator\ProductVariantPricesCalculatorInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Product\Model\ProductOptionValueInterface;
use Sylius\Component\Product\Resolver\ProductVariantResolverInterface;
use Webmozart\Assert\Assert;

final class ItemResolver implements ItemResolverInterface
{
    use FormatAmountTrait;

    public function __construct(
        private readonly ProductVariantResolverInterface $productVariantResolver,
        private readonly ChannelContextInterface $channelContext,
        private readonly ProductVariantPricesCalculatorInterface $productVariantPricesCalculator,
    ) {
    }

    public function resolveFromOrderItem(OrderItemInterface $orderItem): Item
    {
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
            ->setId($variant->getCode())
            ->setName($orderItem->getProductName())
            ->setVariant($variantStr)
            ->setQuantity($orderItem->getQuantity())
            ->setPrice(self::formatAmount($orderItem->getFullDiscountedUnitPrice()))
        ;
    }

    public function resolveFromProduct(ProductInterface $product): Item
    {
        /** @var ProductVariantInterface|null $variant */
        $variant = $this->productVariantResolver->getVariant($product);
        Assert::isInstanceOf($variant, ProductVariantInterface::class);

        $item = Item::create()
            ->setId($product->getCode())
            ->setName($product->getName())
        ;

        try {
            $item->setPrice(self::formatAmount($this->productVariantPricesCalculator->calculate($variant, [
                'channel' => $this->channelContext->getChannel(),
            ])));
        } catch (\Throwable) {
        }

        return $item;
    }
}
