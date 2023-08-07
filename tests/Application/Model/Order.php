<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusAnalyticsPlugin\Application\Model;

use Setono\SyliusAnalyticsPlugin\Model\OrderInterface;
use Setono\SyliusAnalyticsPlugin\Model\OrderTrait;
use Sylius\Component\Core\Model\Order as BaseOrder;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_order")
 */
final class Order extends BaseOrder implements OrderInterface
{
    use OrderTrait;
}
