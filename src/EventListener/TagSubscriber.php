<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener;

use Setono\SyliusAnalyticsPlugin\Context\PropertyContextInterface;
use Setono\TagBagBundle\TagBag\TagBagInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

abstract class TagSubscriber implements EventSubscriberInterface
{
    /**
     * @var TagBagInterface
     */
    protected $tagBag;

    /**
     * @var PropertyContextInterface
     */
    private $propertyContext;

    /**
     * @var array|null
     */
    private $properties;

    public function __construct(TagBagInterface $tagBag, PropertyContextInterface $propertyContext)
    {
        $this->tagBag = $tagBag;
        $this->propertyContext = $propertyContext;
    }

    protected function hasProperties(): bool
    {
        if(null === $this->properties) {
            $this->properties = $this->propertyContext->getProperties();
        }

        return \count($this->properties) > 0;
    }

    protected function getProperties(): array
    {
        if(null === $this->properties) {
            $this->properties = $this->propertyContext->getProperties();
        }

        return $this->properties;
    }

    protected function getOrderItemsArray(OrderItemInterface ...$orderItems): array
    {
        $items = [];

        foreach ($orderItems as $orderItem) {
            // @todo missing parameters: https://developers.google.com/analytics/devguides/collection/gtagjs/enhanced-ecommerce#measure_additions_to_and_removals_from_shopping_carts

            $variant = $orderItem->getVariant();
            if (null === $variant) {
                continue;
            }

            $items[] = $this->createItem($variant->getCode(), $orderItem->getVariantName(), $orderItem->getQuantity(), $orderItem->getDiscountedUnitPrice());
        }

        return $items;
    }

    protected function createItem(
        string $id,
        string $name,
        ?int $quantity = null,
        ?int $price = null,
        ?string $listName = null,
        ?string $brand = null,
        ?string $category = null,
        ?string $variant = null,
        ?int $listPosition = null
    ): array {
        return array_filter([
            'id' => $id,
            'name' => $name,
            'list_name' => $listName,
            'brand' => $brand,
            'category' => $category,
            'variant' => $variant,
            'list_position' => $listPosition,
            'quantity' => $quantity,
            'price' => $this->formatMoney($price),
        ]);
    }

    protected function formatMoney(?int $money): ?string
    {
        if (null === $money) {
            return null;
        }

        return (string) round($money / 100, 2);
    }
}
