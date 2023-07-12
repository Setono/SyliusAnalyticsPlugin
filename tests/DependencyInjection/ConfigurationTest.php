<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusAnalyticsPlugin\DependencyInjection;

use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;
use Setono\SyliusAnalyticsPlugin\DependencyInjection\Configuration;
use Setono\SyliusAnalyticsPlugin\Form\Type\ContainerType;
use Setono\SyliusAnalyticsPlugin\Form\Type\PropertyType;
use Setono\SyliusAnalyticsPlugin\Model\Container;
use Setono\SyliusAnalyticsPlugin\Model\Property;
use Setono\SyliusAnalyticsPlugin\Repository\ContainerRepository;
use Setono\SyliusAnalyticsPlugin\Repository\PropertyRepository;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Resource\Factory\Factory;

final class ConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    protected function getConfiguration(): Configuration
    {
        return new Configuration();
    }

    /**
     * @test
     */
    public function processed_value_contains_required_value(): void
    {
        $this->assertProcessedConfigurationEquals([], [
            'events' => [
                'add_payment_info' => true,
                'add_shipping_info' => true,
                'add_to_cart' => true,
                'begin_checkout' => true,
                'purchase' => true,
                'view_cart' => true,
                'view_item_list' => true,
                'view_item' => true,
            ],
            'resources' => [
                'container' => [
                    'classes' => [
                        'model' => Container::class,
                        'controller' => ResourceController::class,
                        'repository' => ContainerRepository::class,
                        'factory' => Factory::class,
                        'form' => ContainerType::class,
                    ],
                ],
                'property' => [
                    'classes' => [
                        'model' => Property::class,
                        'controller' => ResourceController::class,
                        'repository' => PropertyRepository::class,
                        'factory' => Factory::class,
                        'form' => PropertyType::class,
                    ],
                ],
            ],
        ]);
    }
}
