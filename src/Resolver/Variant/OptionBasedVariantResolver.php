<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Resolver\Variant;

use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Product\Model\ProductOptionValueInterface;

final class OptionBasedVariantResolver implements VariantResolverInterface
{
    public function resolve(ProductVariantInterface $productVariant): ?string
    {
        $optionValues = $productVariant->getOptionValues();
        if ($optionValues->isEmpty()) {
            return null;
        }

        return implode(
            '-',
            $optionValues
                ->map(static fn (ProductOptionValueInterface $productOptionValue) => $productOptionValue->getValue())
                ->toArray(),
        );
    }
}
