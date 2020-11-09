<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\DependencyInjection;

use Setono\SyliusAnalyticsPlugin\Doctrine\ORM\HitRepository;
use Setono\SyliusAnalyticsPlugin\Doctrine\ORM\PropertyRepository;
use Setono\SyliusAnalyticsPlugin\Form\Type\PropertyType;
use Setono\SyliusAnalyticsPlugin\Model\Hit;
use Setono\SyliusAnalyticsPlugin\Model\HitInterface;
use Setono\SyliusAnalyticsPlugin\Model\Property;
use Setono\SyliusAnalyticsPlugin\Model\PropertyInterface;
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
        $treeBuilder = new TreeBuilder('setono_sylius_analytics');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('driver')
                    ->defaultValue(SyliusResourceBundle::DRIVER_DOCTRINE_ORM)
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('server_side_tracking')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('enabled')
                            ->info('If this is true, the plugin will use server side tracking instead of client side (JS) tracking')
                            ->defaultFalse()
                        ->end()
                        ->integerNode('push_delay')
                            ->info('The number of seconds after a hit is recorded and it is pushed to Google')
                            ->defaultValue(600) // 10 minutes
                            ->example(300)
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        $this->addResourcesSection($rootNode);

        return $treeBuilder;
    }

    private function addResourcesSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('property')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(Property::class)->cannotBeEmpty()->end()
                                        ->scalarNode('interface')->defaultValue(PropertyInterface::class)->cannotBeEmpty()->end()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->defaultValue(PropertyRepository::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                        ->scalarNode('form')->defaultValue(PropertyType::class)->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('hit')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(Hit::class)->cannotBeEmpty()->end()
                                        ->scalarNode('interface')->defaultValue(HitInterface::class)->cannotBeEmpty()->end()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->defaultValue(HitRepository::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
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
