<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AvatarType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder->add('imageFile', VichImageType::class, [
			'required' => false,
			'allow_delete' => true,      // case à cocher "Supprimer"
			'download_uri' => false,
			'image_uri' => false,         // aperçu si déjà un avatar
			'label' => 'Photo de profil',
			'constraints' => [
				new Image(maxSize: '5M', mimeTypes: [
					'image/jpeg',
					'image/png',
					'image/webp'
				]),
			],
		]);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => User::class,
			'csrf_protection' => true,
		]);
	}
}
