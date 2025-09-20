<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\DriverPreferences;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class DriverPreferencesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('smokingAllowed')
            ->add('petsAllowed')
            ->add('extras', CollectionType::class, [
                'label'        => false,
                'entry_type'   => TextType::class,
                'entry_options' => [
                    'attr' => ['placeholder' => 'ex: siège enfant'],
                ],
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype'    => true,
                'required'     => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DriverPreferences::class,
        ]);
    }
}
