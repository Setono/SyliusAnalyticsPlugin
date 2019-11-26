<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusAnalyticsPlugin\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Setono\SyliusAnalyticsPlugin\DependencyInjection\SetonoSyliusAnalyticsExtension;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;

final class SetonoSyliusAnalyticsExtensionTest extends AbstractExtensionTestCase
{
    protected function getContainerExtensions(): array
    {
        return [
            new SetonoSyliusAnalyticsExtension(),
        ];
    }

    /**
     * @test
     */
    public function after_loading_the_correct_parameter_has_been_set(): void
    {
        $this->load();

        $this->assertContainerBuilderHasParameter('setono_sylius_analytics.driver', SyliusResourceBundle::DRIVER_DOCTRINE_ORM);
    }
}
