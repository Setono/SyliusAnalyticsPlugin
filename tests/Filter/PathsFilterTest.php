<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusAnalyticsPlugin\Filter;

use PHPUnit\Framework\TestCase;
use Setono\GoogleAnalyticsMeasurementProtocol\Hit\HitBuilder;
use Setono\GoogleAnalyticsMeasurementProtocol\Hit\HitBuilderInterface;
use Setono\SyliusAnalyticsPlugin\Filter\PathsFilter;

/**
 * @covers \Setono\SyliusAnalyticsPlugin\Filter\PathsFilter
 */
final class PathsFilterTest extends TestCase
{
    /**
     * @test
     * @dataProvider urls
     */
    public function it_filters(string $url, bool $expected): void
    {
        $hitBuilder = new HitBuilder(HitBuilderInterface::HIT_TYPE_PAGEVIEW);
        $hitBuilder->setDocumentLocationUrl($url);

        $filter = new PathsFilter(['^/api/', '^(.*)?/ajax/']);
        self::assertSame($expected, $filter->filter($hitBuilder));
    }

    public function urls(): \Generator
    {
        yield ['https://example.com/en_US/ajax/add_to_cart?id=123', false];
        yield ['https://example.com/ajax/add_to_cart?id=123', false];
        yield ['https://example.com/api/v1/product', false];
        yield ['https://example.com/product/123', true];
    }
}
