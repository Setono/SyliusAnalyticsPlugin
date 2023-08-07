<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Model;

use Doctrine\ORM\Mapping as ORM;

trait OrderTrait
{
    /** @ORM\Column(type="string", nullable=true) */
    protected ?string $googleAnalyticsClientId = null;

    /**
     * The Google Analytics client id for the user that made this order
     */
    public function getGoogleAnalyticsClientId(): ?string
    {
        return $this->googleAnalyticsClientId;
    }

    public function setGoogleAnalyticsClientId(?string $googleAnalyticsClientId): void
    {
        $this->googleAnalyticsClientId = $googleAnalyticsClientId;
    }
}
