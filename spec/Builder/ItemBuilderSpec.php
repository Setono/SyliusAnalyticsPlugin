<?php

declare(strict_types=1);

namespace spec\Setono\SyliusAnalyticsPlugin\Builder;

use Setono\SyliusAnalyticsPlugin\Builder\ItemBuilder;

class ItemBuilderSpec extends BuilderSpec
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ItemBuilder::class);
    }

    public function it_builds(): void
    {
        $this->setId('id-1234')
            ->setName('Name 1234')
            ->setListName('List name')
            ->setBrand('Apple')
            ->setCategory('Category name')
            ->setVariant('Grey')
            ->setListPosition(10)
            ->setQuantity(2)
            ->setPrice(123.45)
        ;

        $this->getData()->shouldEqual([
            'id' => 'id-1234',
            'name' => 'Name 1234',
            'list_name' => 'List name',
            'brand' => 'Apple',
            'category' => 'Category name',
            'variant' => 'Grey',
            'list_position' => 10,
            'quantity' => 2,
            'price' => 123.45,
        ]);
    }
}
