<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Doctrine\ORM;

use Setono\SyliusAnalyticsPlugin\Model\PropertyInterface;
use Setono\SyliusAnalyticsPlugin\Repository\PropertyRepositoryInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Channel\Model\ChannelInterface;
use Webmozart\Assert\Assert;

class PropertyRepository extends EntityRepository implements PropertyRepositoryInterface
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
        Assert::allIsInstanceOf($result, PropertyInterface::class);

        return $result;
    }
}
