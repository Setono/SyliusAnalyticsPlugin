<?php

declare(strict_types=1);

namespace spec\Setono\SyliusAnalyticsPlugin\Builder;

use PhpSpec\ObjectBehavior;
use Setono\SyliusAnalyticsPlugin\Builder\BuilderInterface;

abstract class BuilderSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedThrough('create');
    }

    public function it_chains(): void
    {
        $this->setTest('test')->shouldBeAnInstanceOf(BuilderInterface::class);
    }

    abstract public function it_builds(): void;
}
