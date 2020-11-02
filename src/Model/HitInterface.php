<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Model;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;

interface HitInterface extends ResourceInterface, TimestampableInterface
{
    public function getId(): string;

    public function getUrl(): string;
}
