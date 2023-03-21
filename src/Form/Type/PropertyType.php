<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Form\Type;

use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class PropertyType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('measurementId', TextType::class, [
                'label' => 'setono_sylius_analytics.ui.measurement_id',
                'attr' => [
                    'placeholder' => 'setono_sylius_analytics.ui.measurement_id_placeholder',
                ],
            ])
            ->add('apiSecret', TextType::class, [
                'label' => 'setono_sylius_analytics.ui.api_secret',
                'attr' => [
                    'placeholder' => 'setono_sylius_analytics.ui.api_secret_placeholder',
                ],
            ])
            ->add('enabled', CheckboxType::class, [
                'required' => false,
                'label' => 'sylius.ui.enabled',
            ])
            ->add('channels', ChannelChoiceType::class, [
                'multiple' => true,
                'expanded' => true,
                'label' => 'sylius.form.product.channels',
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'setono_sylius_analytics_property';
    }
}
