<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Event;

use Setono\SyliusAnalyticsPlugin\Builder\BuilderInterface;
use Symfony\Component\EventDispatcher\Event;

final class BuilderEvent extends Event
{
    /**
     * @var BuilderInterface
     */
    private $builder;

    private $subject;

    public function __construct(BuilderInterface $builder, $subject = null)
    {
        $this->builder = $builder;
        $this->subject = $subject;
    }

    public function getBuilder(): BuilderInterface
    {
        return $this->builder;
    }

    public function getSubject()
    {
        return $this->subject;
    }
}
