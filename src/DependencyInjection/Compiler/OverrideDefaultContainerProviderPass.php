<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\DependencyInjection\Compiler;

use Setono\SyliusAnalyticsPlugin\Provider\DatabaseContainerProvider;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class OverrideDefaultContainerProviderPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has('setono_google_analytics.container_provider.default')) {
            return;
        }

        $definition = new Definition(DatabaseContainerProvider::class, [
            new Reference('sylius.context.channel'),
            new Reference('setono_sylius_analytics.repository.container'),
        ]);

        $container->setDefinition('setono_google_analytics.container_provider.default', $definition);
    }
}
