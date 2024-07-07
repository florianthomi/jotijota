<?php

namespace App\Controller;

use App\BusinessLogic\Services\Dashboard;
use App\Entity\Edition;
use App\Entity\Group;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    public function __construct(private readonly Dashboard $dashboard)
    {
    }

    #[Route('/', name: 'app_dashboard')]
    public function personal(): Response
    {
        return $this->render('dashboard/personal.html.twig', [
            'stats' => $this->dashboard->getStatsForUser($this->getUser())
        ]);
    }

    #[Route('/dashboard/group/{group}', name: 'app_dashboard_group')]
    public function group(Group $group): Response
    {
        return $this->render('dashboard/group.html.twig', [
            'stats' => $this->dashboard->getStatsForGroup($group),
            'topTens' => $this->dashboard->getTopTenByGroup($group)
        ]);
    }

    #[Route('/dashboard/edition/{edition}', name: 'app_dashboard_edition')]
    public function edition(Edition $edition): Response
    {
        return $this->render('dashboard/group.html.twig', [
            'stats' => $this->dashboard->getStatsForEdition($edition),
            'topTens' => $this->dashboard->getTopTenByEdition($edition)
        ]);
    }
}
