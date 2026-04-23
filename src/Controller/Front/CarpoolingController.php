<?php

namespace App\Controller\Front;

use App\Controller\Base\BaseController;
use App\Entity\Carpooling;
use App\Entity\User;
use App\Form\CarpoolingType;
use App\Repository\CarpoolingRepository;
use App\Repository\DriverPreferencesRepository;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class CarpoolingController extends BaseController
{
    #[Route('/covoiturages/recherche', name: 'app_carpooling_search', methods: ['GET'])]
    public function search(Request $request, CarpoolingRepository $carpoolingRepository): Response
    {
        $departureCity = trim((string) $request->query->get('depart', ''));
        $destinationCity = trim((string) $request->query->get('arrivee', ''));
        $departureDateRaw = trim((string) $request->query->get('departAt', ''));
        $departureDate = null;

        if ($departureDateRaw !== '') {
            $departureDate = \DateTimeImmutable::createFromFormat('Y-m-d', $departureDateRaw) ?: null;
        }

        $trips = $carpoolingRepository->searchUpcomingTrips($departureCity, $destinationCity, $departureDate);
        $hasFilters = $departureCity !== '' || $destinationCity !== '' || $departureDateRaw !== '';

        return $this->render('front/carpooling/search.html.twig', [
            'trips' => $trips,
            'depart' => $departureCity,
            'arrivee' => $destinationCity,
            'departAt' => $departureDate ?? $departureDateRaw,
            'hasFilters' => $hasFilters,
        ]);
    }

    #[Route('/covoiturages/publier', name: 'app_carpooling_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        DriverPreferencesRepository $driverPreferencesRepository,
        VehicleRepository $vehicleRepository,
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        $hasPreferences = null !== $driverPreferencesRepository->findOneBy(['user' => $user]);
        $hasVehicle = $vehicleRepository->count(['owner' => $user, 'active' => true]) > 0;

        if (!$hasPreferences || !$hasVehicle) {
            $this->addFlash('warning', 'Avant de publier un trajet, ajoute tes preferences conducteur et au moins un vehicule.');

            return $this->redirectToRoute('app_driver_preferences_index');
        }

        $trip = new Carpooling();
        $form = $this->createForm(CarpoolingType::class, $trip, [
            'user' => $user,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vehicle = $trip->getVehicle();

            if (null === $vehicle || $vehicle->getOwner() !== $user) {
                $form->addError(new FormError('Le vehicule selectionne est invalide.'));
            } elseif ($trip->getArrivalAt() <= $trip->getDepartureAt()) {
                $form->get('arrivalAt')->addError(new FormError('L heure d arrivee doit etre apres l heure de depart.'));
            } elseif ($trip->getSeatsAvailable() > $vehicle->getSeats()) {
                $form->get('seatsAvailable')->addError(new FormError(sprintf(
                    'Tu ne peux pas proposer plus de %d place(s) avec ce vehicule.',
                    $vehicle->getSeats()
                )));
            } else {
                $trip->setDriver($user);
                $trip->setSeatsTotal($vehicle->getSeats());
                $trip->setStatus('planifie');
                $trip->setEcoTag((bool) $vehicle->isElectric());
                $trip->setDurationMinutes((int) (($trip->getArrivalAt()->getTimestamp() - $trip->getDepartureAt()->getTimestamp()) / 60));

                $entityManager->persist($trip);
                $entityManager->flush();

                $this->addFlash('success', 'Ton trajet a bien ete publie.');

                return $this->redirectToRoute('app_carpooling_search', [
                    'depart' => $trip->getOriginCity(),
                    'arrivee' => $trip->getDestinationCity(),
                    'departAt' => $trip->getDepartureAt()->format('Y-m-d'),
                ]);
            }
        }

        return $this->render('front/carpooling/new.html.twig', [
            'form' => $form,
        ]);
    }
}
