<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\DependencyInjection;

use Exception;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class SetonoSyliusAnalyticsExtension extends AbstractResourceExtension
{
    public function load(array $config, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $config);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $container->setParameter('setono_sylius_analytics.server_side_tracking', $config['server_side_tracking']);

        $loader->load('services.xml');

        if(true === $config['server_side_tracking']) {

        } else {
            $loader->load('services/client_side_tracking/event_listener.xml');
        }

        $this->registerResources('setono_sylius_analytics', $config['driver'], $config['resources'], $container);
    }
}
