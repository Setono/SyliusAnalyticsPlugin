<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Model;

use Sylius\Component\Resource\Model\TimestampableTrait;
use Symfony\Component\Uid\Uuid;

/**
 * @final
 */
class Hit implements HitInterface
{
    use TimestampableTrait;

    /** @var string */
    protected $id;

    /** @var string */
    protected $url;

    public function __construct(string $url)
    {
        $this->id = (string) Uuid::v4();
        $this->url = $url;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
