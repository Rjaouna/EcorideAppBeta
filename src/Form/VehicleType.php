<?php
// src/Form/VehicleType.php
namespace App\Form;

use App\Entity\Vehicle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

// Types de champs
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints as Assert;

class VehicleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentYear = (int) (new \DateTimeImmutable())->format('Y');

        $builder
            ->add('plateNumber', TextType::class, [
                'label' => 'Plaque d’immatriculation',
                'help'  => 'Ex. AA-123-BB (format FR) ou format équivalent.',
                'attr'  => [
                    'placeholder' => 'AA-123-BB',
                    'autocomplete' => 'off',
                    'inputmode' => 'text',
                    'class' => 'text-uppercase',
                    // petit pattern (souple) FR : 2L-3N-2L avec tirets ou espaces
                    'pattern' => '^[A-Za-z]{2}[-\s]?[0-9]{3}[-\s]?[A-Za-z]{2}$',
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 6, 'max' => 14]),
                ],
            ])

            ->add('firstRegistrationAt', DateType::class, [
                'label' => 'Date de première immatriculation',
                'widget' => 'single_text',
                'attr'   => [
                    'max' => (new \DateTimeImmutable())->format('Y-m-d'),
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\LessThanOrEqual('today'),
                ],
            ])

            ->add('brand', TextType::class, [
                'label' => 'Marque',
                'attr'  => ['placeholder' => 'Tesla, Renault, Peugeot…'],
                'constraints' => [new Assert\NotBlank(), new Assert\Length(['max' => 80])],
            ])

            ->add('model', TextType::class, [
                'label' => 'Modèle',
                'attr'  => ['placeholder' => 'Model 3, Zoé, 208…'],
                'constraints' => [new Assert\NotBlank(), new Assert\Length(['max' => 80])],
            ])

            ->add('color', TextType::class, [
                'label' => 'Couleur',
                'attr'  => ['placeholder' => 'Noir, Blanc, Bleu…'],
                'constraints' => [new Assert\NotBlank(), new Assert\Length(['max' => 40])],
            ])

            ->add('seats', IntegerType::class, [
                'label' => 'Places disponibles',
                'help'  => 'De 1 à 8 (hors conducteur).',
                'attr'  => [
                    'min' => 1,
                    'max' => 8,
                    'step' => 1,
                    'placeholder' => '4',
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\GreaterThanOrEqual(1),
                    new Assert\LessThanOrEqual(8),
                ],
            ])

            ->add('isElectric', CheckboxType::class, [
                'label'    => 'Véhicule électrique',
                'required' => false,
                'help'     => 'Cochez pour afficher le badge Éco dans les trajets.',
            ])

           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
            'attr' => [
                'novalidate' => 'novalidate', 
            ],
        ]);
    }
}
