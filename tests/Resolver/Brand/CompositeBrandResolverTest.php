<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusAnalyticsPlugin\Resolver\Brand;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Setono\SyliusAnalyticsPlugin\Resolver\Brand\BrandResolverInterface;
use Setono\SyliusAnalyticsPlugin\Resolver\Brand\CompositeBrandResolver;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;

/**
 * @covers \Setono\SyliusAnalyticsPlugin\Resolver\Brand\CompositeBrandResolver
 */
final class CompositeBrandResolverTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function it_does_not_return_null(): void
    {
        $concreteReturningNull = new class() implements BrandResolverInterface {
            public function resolveFromProduct(ProductInterface $product): ?string
            {
                return null;
            }

            public function resolveFromProductVariant(ProductVariantInterface $productVariant): ?string
            {
                return null;
            }
        };

        $concreteReturningNonEmptyString = new class() implements BrandResolverInterface {
            public function resolveFromProduct(ProductInterface $product): ?string
            {
                return 'brand';
            }

            public function resolveFromProductVariant(ProductVariantInterface $productVariant): ?string
            {
                return 'brand';
            }
        };

        $composite = new CompositeBrandResolver();
        $composite->add($concreteReturningNull);
        $composite->add($concreteReturningNonEmptyString);

        self::assertSame(
            'brand',
            $composite->resolveFromProduct($this->prophesize(ProductInterface::class)->reveal())
        );

        self::assertSame(
            'brand',
            $composite->resolveFromProductVariant($this->prophesize(ProductVariantInterface::class)->reveal())
        );
    }

    /**
     * @test
     */
    public function it_does_not_return_empty_string(): void
    {
        $concreteReturningEmptyString = new class() implements BrandResolverInterface {
            public function resolveFromProduct(ProductInterface $product): ?string
            {
                return '';
            }

            public function resolveFromProductVariant(ProductVariantInterface $productVariant): ?string
            {
                return '';
            }
        };

        $concreteReturningNonEmptyString = new class() implements BrandResolverInterface {
            public function resolveFromProduct(ProductInterface $product): ?string
            {
                return 'brand';
            }

            public function resolveFromProductVariant(ProductVariantInterface $productVariant): ?string
            {
                return 'brand';
            }
        };

        $composite = new CompositeBrandResolver();
        $composite->add($concreteReturningEmptyString);
        $composite->add($concreteReturningNonEmptyString);

        self::assertSame(
            'brand',
            $composite->resolveFromProduct($this->prophesize(ProductInterface::class)->reveal())
        );

        self::assertSame(
            'brand',
            $composite->resolveFromProductVariant($this->prophesize(ProductVariantInterface::class)->reveal())
        );
    }
}
