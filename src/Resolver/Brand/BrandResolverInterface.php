<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Resolver\Brand;

use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;

interface BrandResolverInterface
{
    public function resolveFromProduct(ProductInterface $product): ?string;

    public function resolveFromProductVariant(ProductVariantInterface $productVariant): ?string;
}
