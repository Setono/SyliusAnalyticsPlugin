<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Resolver\Variant;

use Sylius\Component\Core\Model\ProductVariantInterface;

interface VariantResolverInterface
{
    public function resolve(ProductVariantInterface $productVariant): ?string;
}
