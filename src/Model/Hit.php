<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Model;

use Sylius\Component\Resource\Model\TimestampableTrait;
use Symfony\Component\Uid\Uuid;

class Hit implements HitInterface
{
    use TimestampableTrait;

    /** @var string */
    protected $id;

    /** @var string */
    protected $sessionId;

    /** @var bool */
    protected $consent = false;

    /** @var string */
    protected $payload;

    public function __construct()
    {
        $this->id = (string) Uuid::v4();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    public function setSessionId(string $sessionId): void
    {
        $this->sessionId = $sessionId;
    }

    public function hasConsent(): bool
    {
        return $this->consent;
    }

    public function setConsent(bool $consent): void
    {
        $this->consent = $consent;
    }

    public function getPayload(): string
    {
        return $this->payload;
    }

    public function setPayload(string $url): void
    {
        $this->payload = $url;
    }
}
