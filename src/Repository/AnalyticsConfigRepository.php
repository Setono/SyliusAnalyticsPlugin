<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Repository;

use Setono\SyliusAnalyticsPlugin\Model\AnalyticsConfigInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class AnalyticsConfigRepository extends EntityRepository implements AnalyticsConfigRepositoryInterface
{
    public function findConfig(): ?AnalyticsConfigInterface
    {
        return $this->createQueryBuilder('o')
            ->setMaxResults(1)
            ->orderBy('o.id')
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
