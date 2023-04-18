<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Resolver\Variant;

use Setono\CompositeCompilerPass\CompositeService;
use Sylius\Component\Core\Model\ProductVariantInterface;

/**
 * @property list<VariantResolverInterface> $services
 *
 * @extends CompositeService<VariantResolverInterface>
 */
final class CompositeVariantResolver extends CompositeService implements VariantResolverInterface
{
    public function resolve(ProductVariantInterface $productVariant): ?string
    {
        foreach ($this->services as $variantResolver) {
            $val = $variantResolver->resolve($productVariant);
            if (null !== $val) {
                return $val;
            }
        }

        return null;
    }
}
