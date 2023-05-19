<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Event;

use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

/**
 * This is a generic event that you can fire anywhere in your application and the plugin will pick it up
 * and convert it to a view_item_list Google Analytics event
 */
final class ItemListViewed
{
    public string $listId;

    public string $listName;

    /** @var list<ProductInterface> */
    public array $products;

    /**
     * @param list<ProductInterface> $products
     */
    public function __construct(string $listId, string $listName, array $products)
    {
        $this->listId = $listId;
        $this->listName = $listName;
        $this->products = $products;
    }

    /**
     * @param list<ProductInterface> $products
     */
    public static function fromTaxon(TaxonInterface $taxon, array $products): self
    {
        return new self((string) $taxon->getCode(), (string) $taxon->getName(), $products);
    }
}
