<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Form\Type;

use Setono\SyliusAnalyticsPlugin\Entity\GoogleAnalyticConfigInterface;
use Setono\SyliusAnalyticsPlugin\Form\Type\Translation\GoogleAnalyticConfigTranslationType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Valid;

final class GoogleAnalyticConfigType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var GoogleAnalyticConfigInterface $GoogleAnalyticConfig */
        $GoogleAnalyticConfig = $builder->getData();

        $builder
            ->add('trackingId', TextType::class, [
                'label' => 'setono_sylius_analytics_plugin.ui.tracking_id',
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => GoogleAnalyticConfigTranslationType::class,
                'validation_groups' => ['setono'],
                'constraints' => [new Valid()],
            ])
        ;
    }
}
