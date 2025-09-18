<?php

namespace App\Controller\Admin\Api\User;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserCountController extends AbstractController
{
    #[Route('/admin/api/user/count', name: 'admin_api_user_count', methods: ['GET'])]
    public function count(UserRepository $userRepository): JsonResponse
    {
        $userCount = $userRepository->count([]);
        return $this->json(['count' => $userCount]);
    }
}
