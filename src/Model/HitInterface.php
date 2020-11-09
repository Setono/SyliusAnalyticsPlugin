<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Model;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;

interface HitInterface extends ResourceInterface, TimestampableInterface
{
    public function getId(): string;

    public function getSessionId(): string;

    public function setSessionId(string $sessionId): void;

    /**
     * Returns true if you have consent from the end user to push this hit to Google
     */
    public function hasConsent(): bool;

    public function setConsent(bool $consent): void;

    public function getPayload(): string;

    public function setPayload(string $url): void;
}
