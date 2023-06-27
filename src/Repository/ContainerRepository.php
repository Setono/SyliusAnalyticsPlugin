<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Repository;

use Setono\SyliusAnalyticsPlugin\Model\ContainerInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Channel\Model\ChannelInterface;
use Webmozart\Assert\Assert;

class ContainerRepository extends EntityRepository implements ContainerRepositoryInterface
{
    public function findEnabledByChannel(ChannelInterface $channel): array
    {
        $result = $this->createQueryBuilder('o')
            ->andWhere(':channel MEMBER OF o.channels')
            ->andWhere('o.enabled = true')
            ->setParameter('channel', $channel)
            ->getQuery()
            ->getResult()
        ;

        Assert::isArray($result);
        Assert::allIsInstanceOf($result, ContainerInterface::class);

        return $result;
    }
}
