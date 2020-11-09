<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Doctrine\ORM;

use DateInterval;
use Safe\DateTime;
use Setono\SyliusAnalyticsPlugin\Repository\HitRepositoryInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class HitRepository extends EntityRepository implements HitRepositoryInterface
{
    public function updateConsent(bool $consent, string $sessionId): void
    {
        $this->createQueryBuilder('o')
            ->update()
            ->set('o.consent', $consent)
            ->andWhere('o.sessionId = :sessionId')
            ->setParameter('sessionId', $sessionId)
            ->getQuery()
            ->execute()
        ;
    }

    public function findConsentedWithDelay(int $delay): array
    {
        $then = (new DateTime())->sub(new DateInterval("PT{$delay}S"));

        return $this->createQueryBuilder('o')
            ->andWhere('o.createdAt < :then')
            ->andWhere('o.consent = true')
            ->setParameter('then', $then)
            ->setMaxResults(1000) // just to avoid any memory problems
            ->getQuery()
            ->getResult()
        ;
    }
}
