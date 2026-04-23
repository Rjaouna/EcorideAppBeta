<?php

namespace App\Controller;

use App\Entity\Vehicle;
use App\Form\VehicleType;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/vehicle')]
#[IsGranted('ROLE_USER')]
final class VehicleController extends AbstractController
{
    #[Route(name: 'app_vehicle_index', methods: ['GET'])]
    public function index(VehicleRepository $vehicleRepository): Response
    {
        return $this->render('vehicle/index.html.twig', [
            'vehicles' => $vehicleRepository->findBy(['owner' => $this->getUser()]),
        ]);
    }

    #[Route('/new', name: 'app_vehicle_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $vehicle = new Vehicle();
        $form = $this->createForm(VehicleType::class, $vehicle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vehicle->setActive(true);
            $vehicle->setOwner($this->getUser());
            $entityManager->persist($vehicle);
            $entityManager->flush();

            return $this->redirectToRoute('app_driver_preferences_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vehicle/new.html.twig', [
            'vehicle' => $vehicle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vehicle_show', methods: ['GET'])]
    public function show(Vehicle $vehicle): Response
    {
        $this->denyAccessUnlessOwner($vehicle);

        return $this->render('vehicle/show.html.twig', [
            'vehicle' => $vehicle,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_vehicle_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Vehicle $vehicle, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessOwner($vehicle);

        $form = $this->createForm(VehicleType::class, $vehicle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_driver_preferences_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vehicle/edit.html.twig', [
            'vehicle' => $vehicle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vehicle_delete', methods: ['POST'])]
    public function delete(Request $request, Vehicle $vehicle, EntityManagerInterface $entityManager, VehicleRepository $vehicleRepository): Response
    {
        $this->denyAccessUnlessOwner($vehicle);

        if ($vehicleRepository->count(['owner' => $this->getUser()]) <= 1) {
            $this->addFlash('warning', 'En tant que conducteur, vous devez avoir au moins un vehicule enregistre.');

            return $this->redirectToRoute('app_driver_preferences_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($this->isCsrfTokenValid('delete'.$vehicle->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($vehicle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_driver_preferences_index', [], Response::HTTP_SEE_OTHER);
    }

    private function denyAccessUnlessOwner(Vehicle $vehicle): void
    {
        if ($vehicle->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous ne pouvez acceder qu a vos propres vehicules.');
        }
    }
}
