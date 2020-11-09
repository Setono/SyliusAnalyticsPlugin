<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Resolver;

use Symfony\Component\HttpFoundation\Request;

interface ClientIdResolverInterface
{
    /**
     * Returns a client id to use with the TheIconic\Tracking\GoogleAnalytics\Analytics object
     */
    public function resolve(Request $request): string;
}
