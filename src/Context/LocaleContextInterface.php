<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Context;

use Sylius\Component\Locale\Model\LocaleInterface;

interface LocaleContextInterface
{
    public function getLocale(): ?LocaleInterface;
}
