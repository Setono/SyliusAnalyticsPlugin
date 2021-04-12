<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\DependencyInjection\Compiler;

use Setono\SyliusAnalyticsPlugin\Provider\DatabasePropertyProvider;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class OverrideDefaultPropertyProviderPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has('setono_google_analytics_server_side_tracking.provider.default_property_provider')) {
            return;
        }

        $definition = new Definition(DatabasePropertyProvider::class, [
            new Reference('sylius.context.channel'),
            new Reference('setono_sylius_analytics.repository.property'),
        ]);

        $container->setDefinition(
            'setono_google_analytics_server_side_tracking.provider.default_property_provider',
            $definition
        );
    }
}
