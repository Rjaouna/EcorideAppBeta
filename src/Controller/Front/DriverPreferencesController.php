<?php

namespace App\Controller\Front;

use App\Entity\DriverPreferences;
use App\Form\DriverPreferencesType;
use App\Controller\Base\BaseController;
use App\Entity\Vehicle;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\DriverPreferencesRepository;
use App\Repository\VehicleRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[IsGranted('ROLE_USER')]
#[Route('/driver/preferences')]
final class DriverPreferencesController extends BaseController
{
    #[Route('/driver-preferences', name: 'app_driver_preferences_index', methods: ['GET', 'POST'])]
    public function index(
        DriverPreferencesRepository $repo,
        VehicleRepository $vehicleRepo,

    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $vehicles = $vehicleRepo->findBy(['owner' => $user, 'active' => true]);

        // 1) Récupère ou crée la préférence de l'utilisateur
        // Récupère ou crée les préférences
        $preferences = $repo->findOneBy(['user' => $user]);
        $isNewPreferences = false;
        if (!$preferences) {
            $preferences = (new DriverPreferences())->setUser($user);
            $isNewPreferences = true;
        }

        // 2) Crée le formulaire
        $form = $this->createForm(DriverPreferencesType::class, $preferences, [
            // 'action' => $this->generateUrl('app_driver_preferences_update'), // si tu as une route de save
            'method' => 'POST',
        ]);

        $missingPrefs   = !$preferences || !$preferences->getId(); // ou ($isNewPreferences)
        $missingVehicle = empty($vehicles);
        $statusButton = null;

        if ($missingPrefs && $missingVehicle) {
            $message = sprintf(
                'Pour utiliser le mode conducteur, vous devez d’abord définir vos préférences et ajouter un véhicule. '
                    . '<a href="%s">Créer mes préférences</a> et <a href="%s">Ajouter un véhicule</a>.',
                $this->generateUrl('app_driver_preferences_new'),
                $this->generateUrl('app_vehicle_new')
            );
            $statusButton = 'disabled';
        } elseif ($missingPrefs) {
            $message = sprintf(
                'Pour utiliser le mode conducteur, vous devez d’abord définir vos préférences. '
                    . '<a href="%s">Créer mes préférences</a>.',
                $this->generateUrl('app_driver_preferences_new')
            );
            $statusButton = 'disabled';
        } elseif ($missingVehicle) {
            $message = sprintf(
                'Pour utiliser le mode conducteur, vous devez d’abord ajouter un véhicule. '
                    . '<a href="%s">Ajouter un véhicule</a>.',
                $this->generateUrl('app_vehicle_new')
            );
            $statusButton = 'disabled';
        } else {
            $message = 'Vous pouvez basculer entre les modes passager et conducteur.';
            $statusButton = '';
        }



        // 3) Rend la vue
        return $this->render('front/driver_preferences/index.html.twig', [
            'driver_preference' => $preferences,
            'form' => $form->createView(),
            'driverPreference' => $preferences,
            'vehicles' => $vehicles,
            'message' => $message,
            'statusButton' => $statusButton,
        ]);
    }

    #[Route('/switch', name: 'app_front_driver_preferences_switch_mode', methods: ['POST'])]
    public function switch(Request $request, EntityManagerInterface $em, Security $security, DriverPreferencesRepository $preferences, VehicleRepository $vehicles): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (!$this->isCsrfTokenValid('switch_role', $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Jeton CSRF invalide.');
        }

        $user = $this->getUser();
        $choice = $request->request->get('roleChoice'); // passenger | driver | both
        $driverPreference = $preferences->findBy(['user' => $user]);
        $vehicle = $vehicles->findBy(['owner' => $user]);


        $newRoles = ['ROLE_USER'];
        $needsDriverPref = ($choice === 'driver' || $choice === 'both');
        if ($needsDriverPref && !$driverPreference) {
            $this->addFlash('warning', 'Vous devez d\'abord définir vos préférences de conducteur.');
            return $this->redirectToRoute('app_driver_preferences_new');
        }
        if ($needsDriverPref && !$vehicle) {
            $this->addFlash('warning', 'Vous devez d\'abord ajouter un véhicule.');
            return $this->redirectToRoute('app_vehicle_new');
        }

        switch ($choice) {
            case 'driver':
                $newRoles[] = 'ROLE_DRIVER';
                break;

            case 'passenger':
                $newRoles[] = 'ROLE_PASSENGER';
                break;

            case 'both':
            default:
                $newRoles[] = 'ROLE_PASSENGER';
                $newRoles[] = 'ROLE_DRIVER';
                break;
        }

        $user->setRoles(array_values(array_unique($newRoles)));
        $em->flush();

        // reconnecte l'utilisateur pour éviter la déconnexion
        $security->login($user);
        $request->getSession()->migrate(true);

        $this->addFlash('success', 'Votre rôle a été mis à jour.');
        return $this->redirectToRoute('app_driver_preferences_index');
    }

    #[Route('/new', name: 'app_driver_preferences_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $driverPreference = new DriverPreferences();
        $form = $this->createForm(DriverPreferencesType::class, $driverPreference);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $driverPreference->setUser($this->getUser());
            $entityManager->persist($driverPreference);
            $entityManager->flush();

            return $this->redirectToRoute('app_driver_preferences_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front/driver_preferences/new.html.twig', [
            'driver_preference' => $driverPreference,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_driver_preferences_show', methods: ['GET'])]
    public function show(DriverPreferences $driverPreference): Response
    {
        return $this->render('driver_preferences/show.html.twig', [
            'driver_preference' => $driverPreference,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_driver_preferences_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DriverPreferences $driverPreference, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DriverPreferencesType::class, $driverPreference);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_driver_preferences_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front/driver_preferences/edit.html.twig', [
            'driver_preference' => $driverPreference,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_driver_preferences_delete', methods: ['POST'])]
    public function delete(Request $request, DriverPreferences $driverPreference, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $driverPreference->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($driverPreference);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_driver_preferences_index', [], Response::HTTP_SEE_OTHER);
    }
}
