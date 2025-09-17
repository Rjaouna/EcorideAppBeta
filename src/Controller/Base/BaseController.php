<?php

namespace App\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class BaseController extends AbstractController
{
	/* ---------- SECURITY ---------- */
	/** Retourne l’utilisateur ou 403 si non connecté */
	protected function getUserOr403(): UserInterface
	{
		$user = $this->getUser();
		if (!$user instanceof UserInterface) {
			throw new AccessDeniedException('Vous devez être connecté.');
		}
		return $user;
	}

	/** Lève 403 si l’utilisateur n’a pas le droit */
	protected function denyUnlessGrantedOr403(string $attribute, mixed $subject = null): void
	{
		if (!$this->isGranted($attribute, $subject)) {
			throw new AccessDeniedException('Accès refusé.');
		}
	}
}
