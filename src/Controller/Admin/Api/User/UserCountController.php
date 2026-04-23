<?php

namespace App\Controller\Admin\Api\User;

use App\Repository\UserRepository;
use App\Controller\Base\BaseController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class UserCountController extends BaseController
{
    #[Route('/admin/api/user/count', name: 'admin_api_user_count', methods: ['GET'])]
    public function count(UserRepository $userRepository): JsonResponse
    {
        $userCount = $userRepository->count([]);
        return $this->json(['count' => $userCount]);
    }
}
