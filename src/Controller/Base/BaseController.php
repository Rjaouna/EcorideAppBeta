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

	/**
	 * Redirige l'utilisateur vers la page précédente (header HTTP "Referer") si disponible,
	 * sinon vers une route de secours.
	 *
	 * - Utile après un POST/DELETE pour "revenir" à la liste.
	 * - Le code de redirection est 302 par défaut (modifiable via $status).
	 * - ⚠️ Le "Referer" peut être absent ou externe. Si tu veux empêcher
	 *   les redirections hors domaine, ajoute un contrôle (ex: même host).
	 *
	 * @param Request $request        Requête HTTP pour lire l'en-tête "Referer".
	 * @param string  $fallbackRoute  Nom de la route de repli si aucun "Referer".
	 * @param array   $params         Paramètres de la route de repli.
	 * @param int     $status         Code HTTP de redirection (302 par défaut).
	 *
	 * @return RedirectResponse
	 */
	protected function redirectBack(Request $request, string $fallbackRoute, array $params = [], int $status = 302): RedirectResponse
	{
		// Tente d'utiliser la page d'origine (Referer), sinon tombe sur la route de secours.
		$referer = $request->headers->get('referer');

		return $referer
			? $this->redirect($referer, $status)
			: $this->redirectToRoute($fallbackRoute, $params, $status);
	}

}
