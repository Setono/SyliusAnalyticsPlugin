<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Builder;

use Symfony\Component\DependencyInjection\Container;

abstract class Builder implements BuilderInterface
{
    protected $data = [];

    public static function create()
    {
        return new static();
    }

    public function getData(): array
    {
        return $this->data;
    }

    protected function set(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    public function __call($name, array $arguments)
    {
        if(count($arguments) !== 1) {
            return $this;
        }

        if(strpos($name, 'with') !== 0) {
            return $this;
        }

        $key = Container::underscore(substr($name, 4));
        $val = $arguments[0];

        $this->set($key, $val);

        return $this;
    }
}
