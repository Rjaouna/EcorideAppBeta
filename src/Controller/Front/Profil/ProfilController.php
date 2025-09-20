<?php

namespace App\Controller\Front\Profil;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Repository\VehicleRepository;
use App\Controller\Base\BaseController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class ProfilController extends BaseController
{
    #[Route('/front/profil/profil', name: 'app_front_profil')]
    public function index(UserRepository $users, VehicleRepository $vehicleRepo,): Response
    {
        $user = $this->getUser();
        $profil = $users->find($user);
        $vehicles = $vehicleRepo->findBy(['owner' => $user]);

        return $this->render('front/profil/index.html.twig', [
            'controller_name' => 'ProfilController',
            'profil' => $profil,
            'vehicles' => $vehicles,

        ]);
    }
    #[Route('/{id}/edit', name: 'app_profil_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user, ['mode' => 'edit']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_front_profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front/profil/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}
