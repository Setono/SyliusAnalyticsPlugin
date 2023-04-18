<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Resolver\Category;

use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;

/**
 * A category is used on the item when a purchase event, add to cart, and many similar events are fired.
 * It tells Google which category a specific product belongs to.
 *
 * The methods in this interface must return a list of categories, starting with the top level category at index 0.
 * This means if you have a category (breadcrumb) like Apparel > Shirts > Crew > Short sleeve the resulting array would
 * look like this:
 *
 * [
 *   'Apparel', 'Shirts', 'Crew', 'Short sleeve'
 * ]
 */
interface CategoryResolverInterface
{
    /**
     * @return list<string>
     */
    public function resolveFromProduct(ProductInterface $product): array;

    /**
     * @return list<string>
     */
    public function resolveFromProductVariant(ProductVariantInterface $productVariant): array;
}
