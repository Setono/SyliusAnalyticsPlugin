<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusAnalyticsPlugin\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Setono\SyliusAnalyticsPlugin\DependencyInjection\Configuration;
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

        $events = [];
        foreach (Configuration::EVENTS as $event) {
            $events[$event] = true;
        }

        $this->assertContainerBuilderHasParameter('setono_sylius_analytics.events', $events);
        $this->assertContainerBuilderHasParameter('setono_sylius_analytics.driver', SyliusResourceBundle::DRIVER_DOCTRINE_ORM);
    }

    /**
     * @test
     */
    public function event_subscribers_are_registered_by_default(): void
    {
        $this->load();

        foreach (Configuration::EVENTS as $event) {
            $this->assertContainerBuilderHasService(sprintf('setono_sylius_analytics.event_subscriber.%s', $event));
        }
    }
}
