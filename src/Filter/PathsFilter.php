<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Filter;

use Setono\GoogleAnalyticsMeasurementProtocol\Hit\HitBuilderInterface;
use Setono\GoogleAnalyticsServerSideTrackingBundle\Filter\FilterInterface;

final class PathsFilter implements FilterInterface
{
    /** @var list<string> */
    private array $excludedPaths;

    /**
     * @param list<string> $excludedPaths
     */
    public function __construct(array $excludedPaths)
    {
        $this->excludedPaths = $excludedPaths;
    }

    public function filter(HitBuilderInterface $hitBuilder): bool
    {
        $url = $hitBuilder->getDocumentLocationUrl();
        if (null === $url) {
            return true;
        }

        // we only want to filter page view hits
        // other hits are manually created, and we presume it's intentional
        if ($hitBuilder->getHitType() !== HitBuilderInterface::HIT_TYPE_PAGEVIEW) {
            return true;
        }

        $path = parse_url($url, \PHP_URL_PATH);
        if (!is_string($path) || '' === $path) {
            return true;
        }

        foreach ($this->excludedPaths as $excludedPath) {
            if (preg_match(sprintf('~%s~', $excludedPath), $path) === 1) {
                return false;
            }
        }

        return true;
    }
}
