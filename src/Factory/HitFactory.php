<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Factory;

use Setono\SyliusAnalyticsPlugin\Model\HitInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class HitFactory implements HitFactoryInterface
{
    /** @var FactoryInterface */
    private $decoratedFactory;

    public function __construct(FactoryInterface $decoratedFactory)
    {
        $this->decoratedFactory = $decoratedFactory;
    }

    public function createNew(): HitInterface
    {
        /** @var HitInterface $obj */
        $obj = $this->decoratedFactory->createNew();

        return $obj;
    }

    public function createWithData(string $url, string $sessionId): HitInterface
    {
        $obj = $this->createNew();
        $obj->setPayload($url);
        $obj->setSessionId($sessionId);

        return $obj;
    }
}
