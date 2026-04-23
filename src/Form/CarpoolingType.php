<?php

namespace App\Form;

use App\Entity\Carpooling;
use App\Entity\User;
use App\Entity\Vehicle;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class CarpoolingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var User|null $user */
        $user = $options['user'];

        $builder
            ->add('originCity', TextType::class, [
                'label' => 'Ville de depart',
                'attr' => ['placeholder' => 'Lille'],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(max: 50),
                ],
            ])
            ->add('destinationCity', TextType::class, [
                'label' => 'Ville d\'arrivee',
                'attr' => ['placeholder' => 'Paris'],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(max: 50),
                ],
            ])
            ->add('departureAt', DateTimeType::class, [
                'label' => 'Date et heure de depart',
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\GreaterThan('now'),
                ],
            ])
            ->add('arrivalAt', DateTimeType::class, [
                'label' => 'Date et heure d\'arrivee',
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\GreaterThan('now'),
                ],
            ])
            ->add('vehicle', EntityType::class, [
                'class' => Vehicle::class,
                'label' => 'Vehicule',
                'choice_label' => static fn (Vehicle $vehicle): string => sprintf(
                    '%s %s - %s',
                    $vehicle->getBrand(),
                    $vehicle->getModel(),
                    $vehicle->getPlateNumber()
                ),
                'query_builder' => static fn (EntityRepository $repository) => $repository->createQueryBuilder('v')
                    ->andWhere('v.owner = :owner')
                    ->andWhere('v.active = :active')
                    ->setParameter('owner', $user)
                    ->setParameter('active', true)
                    ->orderBy('v.brand', 'ASC')
                    ->addOrderBy('v.model', 'ASC'),
                'placeholder' => 'Choisir un vehicule',
                'constraints' => [
                    new Assert\NotNull(),
                ],
            ])
            ->add('seatsAvailable', IntegerType::class, [
                'label' => 'Places proposees',
                'attr' => [
                    'min' => 1,
                    'max' => 8,
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Positive(),
                    new Assert\LessThanOrEqual(8),
                ],
            ])
            ->add('priceCredits', IntegerType::class, [
                'label' => 'Prix en credits',
                'attr' => [
                    'min' => 1,
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Positive(),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Carpooling::class,
            'user' => null,
        ]);

        $resolver->setAllowedTypes('user', ['null', User::class]);
    }
}
