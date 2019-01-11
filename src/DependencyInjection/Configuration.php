<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\DependencyInjection;

use Setono\SyliusAnalyticsPlugin\Form\Type\AnalyticsConfigType;
use Setono\SyliusAnalyticsPlugin\Model\AnalyticsConfig;
use Setono\SyliusAnalyticsPlugin\Model\AnalyticsConfigInterface;
use Setono\SyliusAnalyticsPlugin\Repository\AnalyticsConfigRepository;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Sylius\Component\Resource\Factory\Factory;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('setono_sylius_analytics');

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('driver')->defaultValue(SyliusResourceBundle::DRIVER_DOCTRINE_ORM)->end()
            ->end()
        ;

        $this->addResourcesSection($rootNode);

        return $treeBuilder;
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addResourcesSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('google_analytics_config')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(AnalyticsConfig::class)->cannotBeEmpty()->end()
                                        ->scalarNode('interface')->defaultValue(AnalyticsConfigInterface::class)->cannotBeEmpty()->end()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->defaultValue(AnalyticsConfigRepository::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                        ->scalarNode('form')->defaultValue(AnalyticsConfigType::class)->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
