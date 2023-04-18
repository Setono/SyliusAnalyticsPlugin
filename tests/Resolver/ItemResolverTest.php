<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusAnalyticsPlugin\Resolver;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\EventDispatcher\EventDispatcherInterface;
use Setono\SyliusAnalyticsPlugin\Resolver\Brand\BrandResolverInterface;
use Setono\SyliusAnalyticsPlugin\Resolver\Item\ItemResolver;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Calculator\ProductVariantPricesCalculatorInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Product\Resolver\ProductVariantResolverInterface;

/**
 * @covers \Setono\SyliusAnalyticsPlugin\Resolver\Item\ItemResolver
 */
final class ItemResolverTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function it_resolves_from_order_item(): void
    {
        $eventDispatcher = $this->prophesize(EventDispatcherInterface::class);
        $productVariantResolver = $this->prophesize(ProductVariantResolverInterface::class);
        $channelContext = $this->prophesize(ChannelContextInterface::class);
        $productVariantPricesCalculator = $this->prophesize(ProductVariantPricesCalculatorInterface::class);
        $variantResolver = $this->prophesize(\Setono\SyliusAnalyticsPlugin\Resolver\Variant\VariantResolverInterface::class);
        $variantResolver->resolve(Argument::type(ProductVariantInterface::class))->willReturn('Large');
        $categoryResolver = $this->prophesize(\Setono\SyliusAnalyticsPlugin\Resolver\Category\CategoryResolverInterface::class);
        $categoryResolver->resolveFromProductVariant(Argument::type(ProductVariantInterface::class))->willReturn([
            'Apparel', 'T-shirts',
        ]);
        $brandResolver = $this->prophesize(BrandResolverInterface::class);
        $brandResolver->resolveFromProductVariant(Argument::type(ProductVariantInterface::class))->willReturn('PHP');

        $resolver = new ItemResolver(
            $eventDispatcher->reveal(),
            $productVariantResolver->reveal(),
            $channelContext->reveal(),
            $productVariantPricesCalculator->reveal(),
            $variantResolver->reveal(),
            $categoryResolver->reveal(),
            $brandResolver->reveal(),
        );

        $productVariant = $this->prophesize(ProductVariantInterface::class);
        $productVariant->getCode()->willReturn('T_SHIRT');

        $orderItem = $this->prophesize(OrderItemInterface::class);
        $orderItem->getProductName()->willReturn('PHP T-shirt');
        $orderItem->getQuantity()->willReturn(2);
        $orderItem->getFullDiscountedUnitPrice()->willReturn(12345);
        $orderItem->getVariant()->willReturn($productVariant->reveal());
        $item = $resolver->resolveFromOrderItem($orderItem->reveal());

        self::assertSame('T_SHIRT', $item->getId());
        self::assertSame('PHP T-shirt', $item->getName());
        self::assertSame(2, $item->getQuantity());
        self::assertSame('Large', $item->getVariant());
        self::assertSame('PHP', $item->getBrand());
        self::assertSame('Apparel', $item->getCategory());
        self::assertSame('T-shirts', $item->getCategory2());
    }
}
