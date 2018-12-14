<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusAnalyticsPlugin\Behat\Page\Admin\GoogleAnalyticConfig;

use Sylius\Behat\Page\Admin\Crud\UpdatePage as BaseUpdatePage;

final class UpdatePage extends BaseUpdatePage implements UpdatePageInterface
{
	public function fillTrackingId(?string $trackingId): void
	{
		$this->getDocument()->fillField('google_analytics_config_trackingId', $trackingId);
	}
}
