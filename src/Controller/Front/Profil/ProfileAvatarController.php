<?php

namespace App\Controller\Front\Profil;

use App\Entity\User;
use App\Form\AvatarType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class ProfileAvatarController extends AbstractController
{
	#[Route('/profil/avatar', name: 'profile_avatar_edit', methods: ['GET', 'POST'])]
	public function edit(Request $request, EntityManagerInterface $em): Response
	{
		/** @var User $user */
		$user = $this->getUser();
		if (!$user instanceof User) {
			throw $this->createAccessDeniedException();
		}

		$form = $this->createForm(AvatarType::class, $user, [
			// pas d’options particulières ici
		]);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			// Vich mettra à jour imageName/imageSize et (via ton setImageFile) updatedAt
			$em->flush();

			$this->addFlash('success', 'Photo de profil mise à jour.');
			// Redirige où tu veux (ex. page profil)
			return $this->redirectToRoute('app_front_profil', ['id' => $user->getId()]);
		}

		return $this->render('front/profil/avatar_edit.html.twig', [
			'form' => $form->createView(),
			'user' => $user,
		]);
	}
}
