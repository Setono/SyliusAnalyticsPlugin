<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Resolver\Brand;

use Setono\CompositeCompilerPass\CompositeService;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;

/**
 * @property list<BrandResolverInterface> $services
 *
 * @extends CompositeService<BrandResolverInterface>
 */
final class CompositeBrandResolver extends CompositeService implements BrandResolverInterface
{
    public function resolveFromProduct(ProductInterface $product): ?string
    {
        foreach ($this->services as $brandResolver) {
            $val = $brandResolver->resolveFromProduct($product);
            if (null !== $val) {
                return $val;
            }
        }

        return null;
    }

    public function resolveFromProductVariant(ProductVariantInterface $productVariant): ?string
    {
        foreach ($this->services as $brandResolver) {
            $val = $brandResolver->resolveFromProductVariant($productVariant);
            if (null !== $val) {
                return $val;
            }
        }

        return null;
    }
}
