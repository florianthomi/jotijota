<?php

namespace App\Controller;

use App\Repository\GroupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(GroupRepository $groupRepository): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'data' => $groupRepository->findAll(),
        ]);
    }
}
