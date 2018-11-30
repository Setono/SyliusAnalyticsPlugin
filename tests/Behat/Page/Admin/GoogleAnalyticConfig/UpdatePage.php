<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusAnalyticsPlugin\Behat\Page\Admin\GoogleAnalyticConfig;

use Sylius\Behat\Page\Admin\Crud\UpdatePage as BaseUpdatePage;

final class UpdatePage extends BaseUpdatePage implements UpdatePageInterface
{
	public function fillTrackingId(string $trackingId): void
	{
		$this->getDocument()->fillField('google_analytic_config_trackingId', $trackingId);
	}

	public function fillName(string $name): void
    {
        $this->getDocument()->fillField('google_analytic_config_translations_en_US_name', $name);
    }
}
