<?php

namespace App\Controller;

use App\Entity\Entry;
use App\Form\EntryType;
use App\Repository\EntryRepository;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(EntryRepository $entryRepository): Response
    {
        $callback = function(array &$element) use ($entryRepository) {
            $element['entries'] = $entryRepository->createQueryBuilder('e')
                ->select('COUNT(e.id)')
                ->andWhere('e.user_id = :id')
                ->setParameter('id', $element['id'])
                ->getQuery()
                ->getSingleScalarResult()
            ;
        };

        $participants = $this->getParticipants($callback);

        usort($participants, static function($a, $b) {
            if ($a['entries'] !== $b['entries']) {
                return $b['entries'] <=> $a['entries'];
            }

            return strtolower($a['last_name']) <=> strtolower($b['last_name']);
        });

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
            preg_match('#^([1-7])([A-Za-z]{2})\d{2}[A-Za-z0-9]$#', $jid, $matches);

            if (empty($matches[2]) || ((int)$matches[1] !== 7 && !Countries::exists($matches[2]))) {
                $form->get('jid')->addError(new FormError('JID invalide'));
            } else {
                $entry->setCountry($matches[2]);
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

    #[Route('/export/{id}', name: 'app_export', defaults: ['id' => null])]
    public function export(EntryRepository $entryRepository, int $id = null): StreamedResponse
    {

        $participants = $this->getParticipants();

        if ($id) {
            $entries = $entryRepository->findBy(['user_id' => $id]);
        } else {
            $entries = $entryRepository->findAll();
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $xlsx = new Xlsx($spreadsheet);

        $header = [
            'JID',
            'Pays',
            'Pseudo',
            'Age',
            'Date d\'ajout',
            'Participant'
        ];

        $sheet->fromArray($header);

        $row = 1;
        foreach ($entries as $entry) {
            $data = [
                $entry->getJid(),
                $entry->getCountry(),
                $entry->getPseudo(),
                $entry->getAge(),
                $entry->getCreatedAt()->format('d.m.Y H:i'),
                $participants[$entry->getUserId()]['last_name'] . ' ' . $participants[$entry->getUserId()]['first_name']
            ];

            $sheet->fromArray($data, null, 'A' . ++$row);
        }

        $reponse = new StreamedResponse();

        $reponse->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $reponse->headers->set(
            'Content-Disposition',
            'attachment; filename="export-entries-' . (new \DateTime())->format('U') . '.xlsx"'
        );

        $reponse->setCallback(function () use ($xlsx)
        {
            $xlsx->save('php://output');
        });

        return $reponse;
    }

    private function getParticipants(callable $callback = null): array
    {
        $participants = [];

        $client = HttpClient::create();

        $url = $this->getParameter('event_url');
        while ($url !== null) {
            $request = $client->request('GET', $url);

            $response = json_decode($request->getContent(), true);

            $url = $response['next_page_link'];

            foreach ($response['event_participations'] as $event_participation) {
                if ($callback) {
                    $callback($event_participation);
                }
                $participants[$event_participation['id']] = $event_participation;
            }
        }

        return $participants;
    }
}
