<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Builder;

use InvalidArgumentException;

trait ItemsAwareBuilderTrait
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param array|BuilderInterface $item
     *
     * @return BuilderInterface
     */
    public function addItem($item): BuilderInterface
    {
        if (!isset($this->data['items'])) {
            $this->data['items'] = [];
        }

        if ($item instanceof BuilderInterface) {
            $item = $item->getData();
        }

        if (!is_array($item)) {
            throw new InvalidArgumentException(sprintf('The item needs to be an array or instance of %s', BuilderInterface::class));
        }

        $this->data['items'][] = $item;

        return $this;
    }
}
