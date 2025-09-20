<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse e-mail',
                'disabled' => $options['mode'] === 'edit',   // ← désactive en édition
                'attr' => [
                    // optionnel : style visuel de lecture seule
                    'readonly' => $options['mode'] === 'edit' ? 'readonly' : null,
                ],
                'constraints' => [
                    new Assert\NotBlank(message: 'Veuillez saisir une adresse e-mail.'),
                    new Assert\Email(message: 'Adresse e-mail invalide.'),
                    new Assert\Length(max: 180),
                ],
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [
                    new Assert\NotBlank(message: 'Veuillez saisir le prénom.'),
                    new Assert\Length(max: 50),
                ],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new Assert\NotBlank(message: 'Veuillez saisir le nom.'),
                    new Assert\Length(max: 50),
                ],
            ])
            ->add('phone', TelType::class, [
                'label' => 'Téléphone',
                'required' => false,
                'constraints' => [
                    new Assert\Length(max: 50),
                ],
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse postale',
                'required' => false,
                'constraints' => [
                    new Assert\Length(max: 255),
                ],
            ])
            ->add('dateOfBirthAt', DateType::class, [
                'label'   => 'Date de naissance',
                'widget'  => 'single_text',
                'required' => false,
                'input'   => 'datetime_immutable',
            ])
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo',
                'constraints' => [
                    new Assert\NotBlank(message: 'Veuillez saisir un pseudo.'),
                    new Assert\Length(max: 50),
                ],
            ])

        ;

        // Ajouter le mot de passe uniquement en mode création
        if ($options['mode'] === 'create') {
            $builder->add('imageFile', VichImageType::class, [
                'label'         => 'Avatar',
                'required'      => false,
                'allow_delete'  => true,
                'download_uri'  => false,
                'image_uri'     => true, // aperçu automatique si dispo
                'constraints'   => [
                    new Assert\Image(
                        maxSize: '5M',
                        mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
                        mimeTypesMessage: 'Formats autorisés : JPG, PNG, WEBP (max 5 Mo).'
                    ),
                ],
            ]);
            $builder->add('plainPassword', PasswordType::class, [
                'label' => 'Mot de passe',
                'mapped' => false, // sera hashé dans le contrôleur
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new Assert\NotBlank(message: 'Veuillez saisir un mot de passe.'),
                    new Assert\Length(min: 8, minMessage: 'Au moins {{ limit }} caractères.'),
                ],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            // 'create' ou 'edit'
            'mode' => 'create',
        ]);
    }
}
