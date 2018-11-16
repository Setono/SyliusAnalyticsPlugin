<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Repository;

use Setono\SyliusAnalyticsPlugin\Entity\GoogleAnalyticConfigInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class AnalyticConfigRepository extends EntityRepository implements AnalyticConfigRepositoryInterface
{
    public function findConfig(): ?GoogleAnalyticConfigInterface
    {
        return $this->createQueryBuilder('o')
            ->setMaxResults(1)
            ->orderBy('o.id')
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
