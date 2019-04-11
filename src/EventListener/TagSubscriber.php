<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener;

use Setono\SyliusAnalyticsPlugin\Context\PropertyContextInterface;
use Setono\SyliusAnalyticsPlugin\Formatter\MoneyFormatter;
use Setono\TagBagBundle\TagBag\TagBagInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
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
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var array|null
     */
    private $properties;

    /**
     * @var MoneyFormatter
     */
    protected $moneyFormatter;

    public function __construct(TagBagInterface $tagBag, PropertyContextInterface $propertyContext, EventDispatcherInterface $eventDispatcher)
    {
        $this->tagBag = $tagBag;
        $this->propertyContext = $propertyContext;
        $this->eventDispatcher = $eventDispatcher;
        $this->moneyFormatter = new MoneyFormatter();
    }

    protected function hasProperties(): bool
    {
        return \count($this->getProperties()) > 0;
    }

    protected function getProperties(): array
    {
        if (null === $this->properties) {
            $this->properties = $this->propertyContext->getProperties();
        }

        return $this->properties;
    }
}
