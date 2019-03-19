<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusAnalyticsPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Setono\SyliusAnalyticsPlugin\Model\PropertyInterface;
use Setono\SyliusAnalyticsPlugin\Repository\PropertyRepositoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class PropertyContext implements Context
{
    /**
     * @var PropertyRepositoryInterface
     */
    private $propertyRepository;

    /**
     * @var FactoryInterface
     */
    private $propertyFactory;

    public function __construct(PropertyRepositoryInterface $propertyRepository, FactoryInterface $propertyFactory)
    {
        $this->propertyRepository = $propertyRepository;
        $this->propertyFactory = $propertyFactory;
    }

    /**
     * @Given the store has a property with tracking id :trackingId
     */
    public function theStoreHasAPropertyWithTrackingId($trackingId): void
    {
        $brand = $this->createProperty($trackingId);

        $this->saveBrand($brand);
    }

    private function createProperty(string $trackingId): PropertyInterface
    {
        /** @var PropertyInterface $property */
        $property = $this->propertyFactory->createNew();

        $property->setTrackingId($trackingId);

        return $property;
    }

    private function saveBrand(PropertyInterface $property): void
    {
        $this->propertyRepository->add($property);
    }
}
