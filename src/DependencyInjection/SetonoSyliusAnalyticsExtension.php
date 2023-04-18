<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\DependencyInjection;

use Setono\SyliusAnalyticsPlugin\Resolver\Brand\BrandResolverInterface;
use Setono\SyliusAnalyticsPlugin\Resolver\Variant\VariantResolverInterface;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class SetonoSyliusAnalyticsExtension extends AbstractResourceExtension
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

        $events = ['add_payment_info', 'add_shipping_info', 'add_to_cart', 'begin_checkout', 'purchase', 'view_cart', 'view_item'];
        foreach ($events as $event) {
            if (isset($config['events'][$event]) && true === $config['events'][$event]) {
                $loader->load('services/conditional/events/' . $event . '.xml');
            }
        }

        $this->registerResources('setono_sylius_analytics', SyliusResourceBundle::DRIVER_DOCTRINE_ORM, $config['resources'], $container);

        $container->registerForAutoconfiguration(BrandResolverInterface::class)
            ->addTag('setono_sylius_analytics.brand_resolver')
        ;

        $container->registerForAutoconfiguration(VariantResolverInterface::class)
            ->addTag('setono_sylius_analytics.variant_resolver')
        ;
    }
}
