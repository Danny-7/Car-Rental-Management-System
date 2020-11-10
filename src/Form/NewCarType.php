<?php

namespace App\Form;

use App\Entity\Car;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Image;

class NewCarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type')
            ->add('amount')
            ->add('category', ChoiceType::class, [
                'mapped' => false,
                'choices' => [
                    'Citadine' => 'Citadine',
                    'Berline' => 'Berline',
                    'Break' => 'Break',
                    'Monospace' => 'Monospace',
                    '4x4, SUV, Crossover' => '4x4, SUV, Crossover',
                    'Coupé' => 'Coupe',
                    'Cabriolet' => 'Cabriolet',
                    'Pick-up' => 'Pick-up'
                ]
            ])
            ->add('fuel', ChoiceType::class, [
                'mapped' => false,
                'choices' => [
                    'Essence' => 'Essence',
                    'Diesel' => 'Diesel',
                    'Aucun' => 'Aucun'
                ]
            ])
            ->add('engine', ChoiceType::class, [
                'mapped' => false,
                'choices' => [
                    'Thermique' => 'Thermique',
                    'Electrique' => 'Electrique',
                    'Hybride' => 'Hybride'
                ]
            ])
            ->add('shift', ChoiceType::class, [
                'mapped' => false,
                'choices' => [
                    'Manuelle' => 'Manuelle',
                    'Automatique' => 'Automatique'
                ]
            ])
            ->add('nb_portes', ChoiceType::class, [
                'mapped' => false,
                'choices' => [
                    '3 portes' => '3 portes',
                    '5 portes' => '5 portes'
                ]
            ])
            ->add('quantity', IntegerType::class)
            ->add('attachment', FileType::class, [
                'mapped' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '2048k',
                        'mimeTypesMessage' => '.png .jpg .jpeg uniquement',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}
