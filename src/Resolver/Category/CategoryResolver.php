<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Resolver\Category;

use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

final class CategoryResolver implements CategoryResolverInterface
{
    public function resolveFromProduct(ProductInterface $product): array
    {
        $taxon = $product->getMainTaxon() ?? $product->getTaxons()->first();
        if (!$taxon instanceof TaxonInterface) {
            return [];
        }

        /**
         * Presume the $product has this taxon hierarchy: Apparel > Shirts > Crew > Short sleeve
         * After the call below, $hierarchy will be ['Crew', 'Shirts', 'Apparel']. Notice that the values will
         * actually still be taxon objects and not strings yet, but I am just showing it like this for readability
         */
        $hierarchy = $taxon->getAncestors()->toArray();

        /**
         * Now we prepend the 'Short sleeve' to the $hierarchy variable, so the resulting array is now:
         * ['Short sleeve', 'Crew', 'Shirts', 'Apparel']
         */
        array_unshift($hierarchy, $taxon);

        /**
         * Now we just need to reverse the array to make sure the top level taxon is first and so on,
         * and finally map the taxon objects to strings and the returned array will be ['Apparel', 'Shirts', 'Crew', 'Short sleeve']
         */
        return array_values(array_map(
            static function (TaxonInterface $taxon): string {
                return (string) $taxon->getName();
            },
            array_reverse($hierarchy),
        ));
    }

    public function resolveFromProductVariant(ProductVariantInterface $productVariant): array
    {
        $product = $productVariant->getProduct();
        if (!$product instanceof ProductInterface) {
            return [];
        }

        return $this->resolveFromProduct($product);
    }
}
