<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Tag;

final class GtagTag implements GtagTagInterface
{
    private $action;
    private $key;
    private $parameters;

    public function __construct(string $action, string $key, array $parameters = [])
    {
        $this->action = $action;
        $this->key = $key;

        $this->parameters = empty($parameters) ? '' : json_encode($parameters);
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getType(): string
    {
        return self::TYPE_SCRIPT;
    }

    public function getTemplate(): string
    {
        return '@SetonoSyliusAnalyticsPlugin/Tag/event.js.twig';
    }

    public function getParameters(): array
    {
        $ret = ['action' => $this->action];

        if ('' !== $this->parameters) {
            $ret['parameters'] = $this->parameters;
        }

        return $ret;
    }
}
