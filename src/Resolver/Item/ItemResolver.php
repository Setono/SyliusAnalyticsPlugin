<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Resolver\Item;

use Psr\EventDispatcher\EventDispatcherInterface;
use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\Item\Item;
use Setono\SyliusAnalyticsPlugin\Event\ItemResolved;
use Setono\SyliusAnalyticsPlugin\Resolver\Brand\BrandResolverInterface;
use Setono\SyliusAnalyticsPlugin\Resolver\Category\CategoryResolverInterface;
use Setono\SyliusAnalyticsPlugin\Resolver\Variant\VariantResolverInterface;
use Setono\SyliusAnalyticsPlugin\Util\FormatAmountTrait;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Calculator\ProductVariantPricesCalculatorInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Product\Resolver\ProductVariantResolverInterface;
use Webmozart\Assert\Assert;

final class ItemResolver implements ItemResolverInterface
{
    use FormatAmountTrait;

    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly ProductVariantResolverInterface $productVariantResolver,
        private readonly ChannelContextInterface $channelContext,
        private readonly ProductVariantPricesCalculatorInterface $productVariantPricesCalculator,
        private readonly VariantResolverInterface $variantResolver,
        private readonly CategoryResolverInterface $categoryResolver,
        private readonly BrandResolverInterface $brandResolver,
    ) {
    }

    public function resolveFromOrderItem(OrderItemInterface $orderItem): Item
    {
        $variant = $orderItem->getVariant();
        Assert::notNull($variant);

        $item = Item::create()
            ->setId($variant->getCode())
            ->setName($orderItem->getProductName())
            ->setVariant($this->variantResolver->resolve($variant))
            ->setBrand($this->brandResolver->resolveFromProductVariant($variant))
            ->setQuantity($orderItem->getQuantity())
            ->setPrice(self::formatAmount($orderItem->getFullDiscountedUnitPrice()))
        ;

        $this->populateCategories($item, $this->categoryResolver->resolveFromProductVariant($variant));

        $this->eventDispatcher->dispatch(new ItemResolved($item, [
            'orderItem' => $orderItem,
            'variant' => $variant,
        ]));

        return $item;
    }

    public function resolveFromProduct(ProductInterface $product): Item
    {
        /** @var ProductVariantInterface|null $variant */
        $variant = $this->productVariantResolver->getVariant($product);
        Assert::isInstanceOf($variant, ProductVariantInterface::class);

        $item = Item::create()
            ->setId($product->getCode())
            ->setName($product->getName())
            ->setBrand($this->brandResolver->resolveFromProduct($product))
        ;

        try {
            $item->setPrice(self::formatAmount($this->productVariantPricesCalculator->calculate($variant, [
                'channel' => $this->channelContext->getChannel(),
            ])));
        } catch (\Throwable) {
        }

        $this->populateCategories($item, $this->categoryResolver->resolveFromProduct($product));

        $this->eventDispatcher->dispatch(new ItemResolved($item, [
            'product' => $product,
            'variant' => $variant,
        ]));

        return $item;
    }

    /**
     * @param list<string> $categories
     */
    private function populateCategories(Item $item, array $categories): void
    {
        foreach ($categories as $idx => $category) {
            // an item only have five categories available
            if ($idx > 4) {
                break;
            }

            $method = sprintf('setCategory%s', 0 === $idx ? '' : (string) ($idx + 1));
            $item->{$method}($category);
        }
    }
}
