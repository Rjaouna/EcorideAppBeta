<?php

namespace App\Controller\Front;

use App\Controller\Base\BaseController;
use App\Repository\CarpoolingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
}
