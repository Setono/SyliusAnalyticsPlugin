<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin;

use Setono\CompositeCompilerPass\CompositeCompilerPass;
use Setono\SyliusAnalyticsPlugin\DependencyInjection\Compiler\OverrideDefaultContainerProviderPass;
use Setono\SyliusAnalyticsPlugin\DependencyInjection\Compiler\OverrideDefaultPropertyProviderPass;
use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Sylius\Bundle\ResourceBundle\AbstractResourceBundle;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class SetonoSyliusAnalyticsPlugin extends AbstractResourceBundle
{
    use SyliusPluginTrait;

    public function getSupportedDrivers(): array
    {
        return [
            SyliusResourceBundle::DRIVER_DOCTRINE_ORM,
        ];
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new OverrideDefaultContainerProviderPass());
        $container->addCompilerPass(new OverrideDefaultPropertyProviderPass());

        $container->addCompilerPass(new CompositeCompilerPass(
            'setono_sylius_analytics.resolver.brand.composite',
            'setono_sylius_analytics.brand_resolver',
        ));

        $container->addCompilerPass(new CompositeCompilerPass(
            'setono_sylius_analytics.resolver.variant.composite',
            'setono_sylius_analytics.variant_resolver',
        ));
    }
}
