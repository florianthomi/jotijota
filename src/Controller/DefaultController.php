<?php

namespace App\Controller;

use App\Entity\Entry;
use App\Form\EntryType;
use App\Repository\EntryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        $participants = [];

        $client = HttpClient::create();

        $url = $this->getParameter('event_url');
        while ($url !== null) {
            $request = $client->request('GET', $url);

            $response = json_decode($request->getContent(), true);

            $url = $response['next_page_link'];

            foreach ($response['event_participations'] as $event_participation) {
                $participants[$event_participation['id']] = $event_participation;
            }
        }

        return $this->render('default/index.html.twig', [
            'participants' => $participants
        ]);
    }

    #[Route('/detail/{id}', name: 'app_detail')]
    public function detail(Request $request, int $id, EntryRepository $entryRepository): Response
    {
        $entry = new Entry();
        $entry->setUserId($id);

        $form = $this->createForm(EntryType::class, $entry, [
            'attr' => [
                'id' => 'form_entry'
            ]
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $jid = $form->get('jid')->getData();
            preg_match('#^[1-7]([A-Za-z]{2})\d{2}[A-Za-z0-9]$#', $jid, $matches);

            if (empty($matches[1]) || !Countries::exists($matches[1])) {
                $form->get('jid')->addError(new FormError('JID invalide'));
            } else {
                $entry->setCountry($matches[1]);
                $entryRepository->save($entry, true);
                $this->addFlash('success', 'Entrée bien enregistrée !');
                return $this->redirectToRoute('app_detail', ['id' => $id]);
            }
        }

        $entries = $entryRepository->findBy(['user_id' => $id], ['createdAt' => 'DESC']);

        return $this->render('default/detail.html.twig', [
            'entries' => $entries,
            'id'      => $id,
            'form'    => $form->createView()
        ]);
    }

    #[Route('/map', name: 'app_map')]
    public function map(): Response
    {
        return $this->render('default/map.html.twig');
    }

    #[Route('/data/{id}', name: 'app_data', defaults: ['id' => null])]
    public function data(EntryRepository $entryRepository, int $id = null): JsonResponse
    {
        $countries = Countries::getCountryCodes();
        $entries = $entryRepository->statsByCountries($id);

        $data = [];

        foreach ($countries as $country) {
            $data[$country] = $entries[$country] ?? 0;
        }

        return new JsonResponse($data);
    }
}
