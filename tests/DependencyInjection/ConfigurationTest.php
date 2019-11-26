<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusAnalyticsPlugin\DependencyInjection;

use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;
use Setono\SyliusAnalyticsPlugin\DependencyInjection\Configuration;
use Setono\SyliusAnalyticsPlugin\Doctrine\ORM\PropertyRepository;
use Setono\SyliusAnalyticsPlugin\Form\Type\PropertyType;
use Setono\SyliusAnalyticsPlugin\Model\Property;
use Setono\SyliusAnalyticsPlugin\Model\PropertyInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
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
            'driver' => SyliusResourceBundle::DRIVER_DOCTRINE_ORM,
            'resources' => [
                'property' => [
                    'classes' => [
                        'model' => Property::class,
                        'interface' => PropertyInterface::class,
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
