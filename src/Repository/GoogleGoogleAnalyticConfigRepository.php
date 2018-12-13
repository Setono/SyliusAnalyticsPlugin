<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Repository;

use Setono\SyliusAnalyticsPlugin\Model\GoogleAnalyticConfigInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class GoogleGoogleAnalyticConfigRepository extends EntityRepository implements GoogleAnalyticConfigRepositoryInterface
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
