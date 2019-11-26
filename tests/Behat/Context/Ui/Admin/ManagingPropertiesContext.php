<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusAnalyticsPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Setono\SyliusAnalyticsPlugin\Model\PropertyInterface;
use Tests\Setono\SyliusAnalyticsPlugin\Behat\Page\Admin\Property\CreatePropertyPage;
use Tests\Setono\SyliusAnalyticsPlugin\Behat\Page\Admin\Property\IndexPropertyPage;
use Tests\Setono\SyliusAnalyticsPlugin\Behat\Page\Admin\Property\UpdatePropertyPage;
use Webmozart\Assert\Assert;

final class ManagingPropertiesContext implements Context
{
    /** @var IndexPropertyPage */
    private $indexPropertyPage;

    /** @var CreatePropertyPage */
    private $createPropertyPage;

    /** @var UpdatePropertyPage */
    private $updatePropertyPage;

    public function __construct(IndexPropertyPage $indexPropertyPage, CreatePropertyPage $createPropertyPage, UpdatePropertyPage $updatePropertyPage)
    {
        $this->indexPropertyPage = $indexPropertyPage;
        $this->createPropertyPage = $createPropertyPage;
        $this->updatePropertyPage = $updatePropertyPage;
    }

    /**
     * @Given I want to create a new property
     */
    public function iWantToCreateANewProperty(): void
    {
        $this->createPropertyPage->open();
    }

    /**
     * @When I fill the tracking id with :id
     */
    public function iFillTheTrackingId($id): void
    {
        $this->createPropertyPage->specifyTrackingId($id);
    }

    /**
     * @When I enable all channels
     */
    public function iEnableAllChannels(): void
    {
        $this->createPropertyPage->enableChannels();
    }

    /**
     * @When I add it
     */
    public function iAddIt(): void
    {
        $this->createPropertyPage->create();
    }

    /**
     * @Then the property :trackingId should appear in the store
     */
    public function thePropertyShouldAppearInTheStore($trackingId): void
    {
        $this->indexPropertyPage->open();

        Assert::true(
            $this->indexPropertyPage->isSingleResourceOnPage(['trackingId' => $trackingId]),
            sprintf('Property %s should exist but it does not', $trackingId)
        );
    }

    /**
     * @Given I want to update the property with tracking id :property
     */
    public function iWantToUpdateThePropertyWithTrackingId(PropertyInterface $property): void
    {
        $this->updatePropertyPage->open([
            'id' => $property->getId(),
        ]);
    }

    /**
     * @When I update the property with tracking id :trackingId
     */
    public function iUpdateThePropertyWithTrackingId($trackingId): void
    {
        $this->updatePropertyPage->specifyTrackingId($trackingId);
    }

    /**
     * @When I save my changes
     */
    public function iSaveMyChanges(): void
    {
        $this->updatePropertyPage->saveChanges();
    }

    /**
     * @Then this property's tracking id should be :trackingId
     */
    public function thisPropertysTrackingIdShouldBe($trackingId): void
    {
        Assert::eq($trackingId, $this->updatePropertyPage->getTrackingId());
    }
}
