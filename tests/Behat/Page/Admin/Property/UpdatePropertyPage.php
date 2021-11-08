<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusAnalyticsPlugin\Behat\Page\Admin\Property;

use Sylius\Behat\Page\Admin\Crud\UpdatePage;

class UpdatePropertyPage extends UpdatePage
{
    public function specifyTrackingId($id): void
    {
        $this->getElement('tracking_id')->setValue($id);
    }

    public function getTrackingId(): string
    {
        return $this->getElement('tracking_id')->getValue();
    }

    /**
     * @inheritdoc
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'tracking_id' => '#setono_sylius_analytics_property_trackingId',
        ]);
    }
}
