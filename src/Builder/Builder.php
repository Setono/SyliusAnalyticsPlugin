<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Builder;

use Symfony\Component\DependencyInjection\Container;

abstract class Builder implements BuilderInterface
{
    /**
     * @var array
     */
    protected $data = [];

    private function __construct()
    {
    }

    public static function create()
    {
        return new static();
    }

    public static function createFromJson(string $json)
    {
        $new = new static();
        $new->data = json_decode($json, true);

        return $new;
    }

    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return string
     *
     * @throws \Safe\Exceptions\JsonException
     */
    public function getJson(): string
    {
        return \Safe\json_encode($this->data);
    }

    public function __call($name, array $arguments)
    {
        if (count($arguments) !== 1) {
            return;
        }

        if (strpos($name, 'set') !== 0) {
            return;
        }

        $key = Container::underscore(substr($name, 3));
        $val = $arguments[0];

        if (null === $val) {
            return;
        }

        if (is_callable($val)) {
            $val = $val();
        } elseif ($val instanceof BuilderInterface) {
            $val = $val->getData();
        } elseif (is_object($val) && method_exists($val, '__toString')) {
            $val = (string) $val;
        }

        if (!is_scalar($val) && !is_array($val)) {
            throw new \InvalidArgumentException(sprintf('Unexpected type %s. Expected types are: callable, %s or scalar', is_object($val) ? get_class($val) : gettype($val), BuilderInterface::class));
        }

        $this->data[$key] = $val;

        return $this;
    }
}
