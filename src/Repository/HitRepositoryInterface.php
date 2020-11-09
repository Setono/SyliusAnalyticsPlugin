<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Repository;

use Setono\SyliusAnalyticsPlugin\Model\HitInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface HitRepositoryInterface extends RepositoryInterface
{
    /**
     * This will update all the hits with the given session id and consent status
     */
    public function updateConsent(bool $consent, string $sessionId): void;

    /**
     * Returns hits where consent is true and the created date is before 'now - $delay'
     *
     * @param int $delay in seconds
     *
     * @return HitInterface[]
     */
    public function findConsentedWithDelay(int $delay): array;
}
