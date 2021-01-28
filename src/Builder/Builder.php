<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Builder;

use InvalidArgumentException;
use const JSON_INVALID_UTF8_IGNORE;
use const JSON_INVALID_UTF8_SUBSTITUTE;
use const JSON_PRESERVE_ZERO_FRACTION;
use const JSON_UNESCAPED_SLASHES;
use function Safe\json_decode;
use function Safe\json_encode;
use function Safe\sprintf;
use Symfony\Component\DependencyInjection\Container;

abstract class Builder implements BuilderInterface
{
    protected array $data = [];

    private function __construct()
    {
    }

    /**
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @return static
     */
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

    public function getJson(): string
    {
        return json_encode($this->data,
            JSON_UNESCAPED_SLASHES | JSON_PRESERVE_ZERO_FRACTION | JSON_INVALID_UTF8_IGNORE | JSON_INVALID_UTF8_SUBSTITUTE);
    }

    /**
     * @param string $name
     *
     * @return static
     */
    public function __call($name, array $arguments)
    {
        if (count($arguments) !== 1) {
            return $this;
        }

        if (mb_strpos($name, 'set') !== 0) {
            return $this;
        }

        $key = Container::underscore(mb_substr($name, 3));
        $val = $arguments[0];

        if (null === $val) {
            return $this;
        }

        if (is_callable($val)) {
            $val = $val();
        } elseif ($val instanceof BuilderInterface) {
            $val = $val->getData();
        } elseif (is_object($val) && method_exists($val, '__toString')) {
            $val = (string) $val;
        }

        if (!is_scalar($val) && !is_array($val)) {
            throw new InvalidArgumentException(sprintf(
                'Unexpected type %s. Expected types are: callable, %s or scalar',
                is_object($val) ? get_class($val) : gettype($val), BuilderInterface::class
            ));
        }

        $this->data[$key] = $val;

        return $this;
    }
}
