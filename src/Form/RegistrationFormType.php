<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;

// Types
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;

// Constraints
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Email
            ->add('email', EmailType::class, [
                'label' => 'Adresse e-mail',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez renseigner votre e-mail.']),
                    new Email(['message' => 'Adresse e-mail invalide.']),
                    new Length([
                        'max' => 180,
                        'maxMessage' => 'Votre e-mail ne doit pas dépasser {{ limit }} caractères.',
                    ]),
                ],
                'attr' => [
                    'autocomplete' => 'email',
                    'placeholder' => 'vous@exemple.com',
                ],
            ])

            // Pseudo
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez choisir un pseudo.']),
                    new Length([
                        'min' => 3,
                        'max' => 50,
                        'minMessage' => 'Le pseudo doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le pseudo ne doit pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9_\-\.]+$/u',
                        'message' => 'Le pseudo ne doit contenir que des lettres, chiffres, tirets, underscores ou points.',
                    ]),
                ],
                'attr' => ['placeholder' => 'ex: alex_92'],
            ])

            // Prénom
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez renseigner votre prénom.']),
                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Le prénom doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le prénom ne doit pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^[\p{L}\p{M} \'\-]+$/u',
                        'message' => 'Le prénom ne doit contenir que des lettres, espaces, apostrophes ou tirets.',
                    ]),
                ],
                'attr' => ['autocomplete' => 'given-name'],
            ])

            // Nom
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez renseigner votre nom.']),
                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le nom ne doit pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^[\p{L}\p{M} \'\-]+$/u',
                        'message' => 'Le nom ne doit contenir que des lettres, espaces, apostrophes ou tirets.',
                    ]),
                ],
                'attr' => ['autocomplete' => 'family-name'],
            ])

            // Téléphone
            ->add('phone', TelType::class, [
                'label' => 'Téléphone',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez renseigner votre téléphone.']),
                    new Length([
                        'min' => 6,
                        'max' => 20,
                        'minMessage' => 'Le téléphone doit contenir au moins {{ limit }} chiffres.',
                        'maxMessage' => 'Le téléphone ne doit pas dépasser {{ limit }} caractères.',
                    ]),
                    // Version internationale (E.164) simplifiée OU FR :
                    // new Regex(['pattern' => '/^\+?[0-9 ]{6,20}$/', 'message' => 'Format de téléphone invalide.']),
                    new Regex(['pattern' => '/^(\+33|0)[1-9](\d{2}){4}$/', 'message' => 'Format de téléphone FR invalide.']),
                ],
                'attr' => ['autocomplete' => 'tel'],
            ])

            // Adresse
            ->add('address', TextareaType::class, [
                'label' => 'Adresse postale',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez renseigner votre adresse.']),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'L’adresse ne doit pas dépasser {{ limit }} caractères.',
                    ]),
                ],
                'attr' => [
                    'rows' => 2,
                    'placeholder' => 'N°, rue, ville, code postal…',
                    'autocomplete' => 'street-address',
                ],
            ])

            // Date de naissance
            ->add('dateOfBirthAt', DateType::class, [
                'label'  => 'Date de naissance',
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
                'required' => false,
                'attr'   => ['max' => (new \DateTime())->format('Y-m-d')],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez renseigner votre date de naissance.']),
                    // Exemple si tu veux vérifier un âge mini :
                    // new LessThan(['value' => '-16 years', 'message' => 'Vous devez avoir au moins 16 ans.']),
                ],
            ])

            // // Photo de profil (upload non mappé)
            // ->add('photo', FileType::class, [
            //     'label' => 'Photo de profil (JPEG/PNG, ≤ 2 Mo)',
            //     'mapped' => false,
            //     'required' => false,
            //     'constraints' => [
            //         new Image([
            //             'maxSize' => '2M',
            //             'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
            //             'mimeTypesMessage' => 'Formats autorisés : JPEG, PNG, WEBP.',
            //         ]),
            //     ],
            // ])

            // Mot de passe
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Mot de passe',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password', 'placeholder' => 'Au moins 8 caractères'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir un mot de passe.']),
                    new Length([
                        'min' => 8,
                        'max' => 4096,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères.',
                    ]),
                    // Option : complexité minimale (1 minuscule, 1 majuscule, 1 chiffre)
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
                        'message' => 'Le mot de passe doit contenir au moins une minuscule, une majuscule et un chiffre.',
                    ]),
                ],
            ])

            // CGU
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'J’accepte les conditions générales et la politique de confidentialité',
                'mapped' => false,
                'constraints' => [
                    new IsTrue(['message' => 'Vous devez accepter nos conditions.']),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
