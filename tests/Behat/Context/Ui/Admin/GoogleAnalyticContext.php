<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusAnalyticsPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Setono\SyliusAnalyticsPlugin\Context\AnalyticConfigContextInterface;
use Sylius\Behat\NotificationType;
use Sylius\Behat\Service\NotificationCheckerInterface;
use Tests\Setono\SyliusAnalyticsPlugin\Behat\Page\Admin\GoogleAnalyticConfig\UpdatePageInterface;

final class GoogleAnalyticContext implements Context
{
    /** @var UpdatePageInterface */
    private $updatePage;

    /** @var NotificationCheckerInterface */
    private $notificationChecker;

    /** @var AnalyticConfigContextInterface */
    private $analyticConfigContext;

    public function __construct(
        UpdatePageInterface $updatePage,
        NotificationCheckerInterface $notificationChecker,
        AnalyticConfigContextInterface $analyticConfigContext)
    {
        $this->updatePage = $updatePage;
        $this->notificationChecker = $notificationChecker;
        $this->analyticConfigContext = $analyticConfigContext;
    }

    /**
     * @When I go to the create google analytic page
     */
    public function iGoToTheCreateGoogleAnalyticPage(): void
    {
        $this->updatePage->open(['id' => $this->analyticConfigContext->getConfig()->getId()]);
    }

    /**
     * @When I fill the trackingId with :trackingId
     */
    public function iFillTheTrackingidWith($trackingId): void
    {
        $this->updatePage->fillTrackingId($trackingId);
    }

    /**
     * @When I name it :name
     */
    public function iNameIt(string $name): void
    {
        $this->updatePage->fillName($name);
    }

    /**
     * @When I add it
     */
    public function iAddIt(): void
    {
        $this->updatePage->saveChanges();
    }

    /**
     * @Then I should be notified that it has been successfully created
     */
    public function iShouldBeNotifiedThatItHasBeenSuccessfullyCreated(): void
    {
        $this->notificationChecker->checkNotification('has been successfully updated.', NotificationType::success());
    }
}
