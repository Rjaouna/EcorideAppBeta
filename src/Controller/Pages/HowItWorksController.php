<?php

namespace App\Controller\Pages;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HowItWorksController extends AbstractController
{
    #[Route('/pages/how/it/works', name: 'app_pages_how_it_works')]
    public function index(): Response
    {
        return $this->render('pages/how_it_works/index.html.twig', [
            'controller_name' => 'HowItWorksController',
        ]);
    }
}
