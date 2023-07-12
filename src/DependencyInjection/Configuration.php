<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\DependencyInjection;

use Setono\SyliusAnalyticsPlugin\Form\Type\ContainerType;
use Setono\SyliusAnalyticsPlugin\Form\Type\PropertyType;
use Setono\SyliusAnalyticsPlugin\Model\Container;
use Setono\SyliusAnalyticsPlugin\Model\Property;
use Setono\SyliusAnalyticsPlugin\Repository\ContainerRepository;
use Setono\SyliusAnalyticsPlugin\Repository\PropertyRepository;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Resource\Factory\Factory;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * Holds the Google Analytics events available in this plugin
     */
    public const EVENTS = [
        'add_payment_info',
        'add_shipping_info',
        'add_to_cart',
        'begin_checkout',
        'purchase',
        'view_cart',
        'view_item_list',
        'view_item',
    ];

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('setono_sylius_analytics');
        $rootNode = $treeBuilder->getRootNode();

        $this->addEventsSection($rootNode);
        $this->addResourcesSection($rootNode);

        return $treeBuilder;
    }

    private function addEventsSection(ArrayNodeDefinition $node): void
    {
        $eventsNode = $node->addDefaultsIfNotSet()->children()->arrayNode('events')->addDefaultsIfNotSet()->children();

        foreach (self::EVENTS as $event) {
            $eventsNode->booleanNode($event)->defaultTrue();
        }
    }

    private function addResourcesSection(ArrayNodeDefinition $node): void
    {
        /** @psalm-suppress MixedMethodCall,PossiblyNullReference,UndefinedInterfaceMethod */
        $node
            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('container')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(Container::class)->cannotBeEmpty()->end()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->defaultValue(ContainerRepository::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                        ->scalarNode('form')->defaultValue(ContainerType::class)->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('property')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(Property::class)->cannotBeEmpty()->end()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->defaultValue(PropertyRepository::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                        ->scalarNode('form')->defaultValue(PropertyType::class)->cannotBeEmpty()->end()
        ;
    }
}
