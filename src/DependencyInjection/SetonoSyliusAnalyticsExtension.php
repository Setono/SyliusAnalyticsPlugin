<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\DependencyInjection;

use Setono\SyliusAnalyticsPlugin\Resolver\Brand\BrandResolverInterface;
use Setono\SyliusAnalyticsPlugin\Resolver\Variant\VariantResolverInterface;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class SetonoSyliusAnalyticsExtension extends AbstractResourceExtension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        /**
         * @psalm-suppress PossiblyNullArgument
         *
         * @var array{events: array<string, bool>, resources: array<string, mixed>} $config
         */
        $config = $this->processConfiguration($this->getConfiguration([], $container), $configs);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $container->setParameter('setono_sylius_analytics.events', $config['events']);

        $loader->load('services.xml');

        foreach ($config['events'] as $event => $enabled) {
            if (!$enabled) {
                continue;
            }

            $loader->load('services/conditional/events/' . $event . '.xml');
        }

        $this->registerResources(
            'setono_sylius_analytics',
            SyliusResourceBundle::DRIVER_DOCTRINE_ORM,
            $config['resources'],
            $container
        );

        $container->registerForAutoconfiguration(BrandResolverInterface::class)
            ->addTag('setono_sylius_analytics.brand_resolver');

        $container->registerForAutoconfiguration(VariantResolverInterface::class)
            ->addTag('setono_sylius_analytics.variant_resolver');
    }

    public function prepend(ContainerBuilder $container): void
    {
        $container->prependExtensionConfig('framework', [
            'messenger' => [
                'buses' => [
                    'setono_sylius_analytics.command_bus' => null,
                ],
            ],
        ]);
    }
}
