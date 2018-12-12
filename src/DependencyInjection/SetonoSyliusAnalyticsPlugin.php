<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\DependencyInjection;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class SetonoSyliusAnalyticsPlugin extends AbstractResourceExtension
{
    public function load(array $config, ContainerBuilder $container)
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $config);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $container->setParameter('setono.sylius_analytics_plugin.google_analytics_config', $config['google_analytics_config']);
        $loader->load('services.yml');
        $this->registerResources('setono_sylius_analytics', $config['driver'], $config['resources'], $container);
    }
}
