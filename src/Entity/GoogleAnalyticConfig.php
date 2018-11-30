<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Entity;

use Sylius\Component\Resource\Model\ToggleableTrait;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TranslationInterface;

class GoogleAnalyticConfig implements GoogleAnalyticConfigInterface
{
    use ToggleableTrait;
    use TranslatableTrait {
        __construct as protected initializeTranslationsCollection;
    }

    public function __construct()
    {
        $this->initializeTranslationsCollection();
    }

    /** @var int */
    protected $id;

    /** @var string */
    protected $trackingId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->GoogleAnalyticConfigTranslation()->getName();
    }

    public function setName(?string $name): void
    {
        $this->GoogleAnalyticConfigTranslation()->setName($name);
    }

    public function getTrackingId(): ?string
    {
        return $this->trackingId;
    }

    public function setTrackingId(?string $trackingId): void
    {
        $this->trackingId = $trackingId;
    }

    /**
     * @return GoogleAnalyticConfigTranslationInterface|TranslationInterface
     */
    protected function GoogleAnalyticConfigTranslation(): TranslationInterface
    {
        return $this->getTranslation();
    }

    protected function createTranslation(): GoogleAnalyticConfigTranslation
    {
        return new GoogleAnalyticConfigTranslation();
    }
}
