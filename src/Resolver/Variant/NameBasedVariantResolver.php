<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Resolver\Variant;

use Sylius\Component\Core\Model\ProductVariantInterface;

final class NameBasedVariantResolver implements VariantResolverInterface
{
    public function resolve(ProductVariantInterface $productVariant): ?string
    {
        return $productVariant->getName();
    }
}
