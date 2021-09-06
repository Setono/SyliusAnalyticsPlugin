<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Event;

use Setono\GoogleAnalyticsMeasurementProtocol\DTO\DTOInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

final class GenericDataEvent
{
    private DTOInterface $data;

    private ResourceInterface $resource;

    public function __construct(DTOInterface $data, ResourceInterface $resource)
    {
        $this->data = $data;
        $this->resource = $resource;
    }

    public function getData(): DTOInterface
    {
        return $this->data;
    }

    public function getResource(): ResourceInterface
    {
        return $this->resource;
    }
}
