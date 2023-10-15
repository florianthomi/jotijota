<?php

namespace App\Controller;

use App\Entity\Entry;
use App\Entity\Member;
use App\Form\EntryType;
use App\Repository\EntryRepository;
use App\Repository\MemberRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    #[IsGranted('ROLE_USER')]
    public function index(MemberRepository $memberRepository): Response
    {
        $participants = $memberRepository->createQueryBuilder('m')
            ->orderBy('SIZE(m.entries)', 'DESC')
            ->addOrderBy('m.last_name', 'ASC')
            ->getQuery()
            ->getResult()
        ;

        return $this->render('default/index.html.twig', [
            'participants' => $participants
        ]);
    }

    #[Route('/detail/{id}', name: 'app_detail')]
    #[IsGranted('ROLE_USER')]
    public function detail(Request $request, Member $member, EntryRepository $entryRepository, TranslatorInterface $translator): Response
    {
        $entry = new Entry();
        $entry->setUserId($member->getId());
        $entry->setMember($member);

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
                $form->get('jid')->addError(new FormError($translator->trans('JID invalide')));
            } else {
                $entry->setCountry($matches[2]);
                $entryRepository->save($entry, true);
                $this->addFlash('success', $translator->trans('Entrée bien enregistrée !'));
                return $this->redirectToRoute('app_detail', ['id' => $member->getId()]);
            }
        }

        $entries = $entryRepository->findBy(['member' => $member], ['createdAt' => 'DESC']);

        return $this->render('default/detail.html.twig', [
            'entries' => $entries,
            'id'      => $member->getId(),
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

        $data = array_fill_keys($countries, 0);

        foreach ($entries as $country => $entry) {
            $data[$country] = $entry['total'] ?? 0;
        }

        return new JsonResponse($data);
    }

    #[Route('/export/{id}', name: 'app_export', defaults: ['id' => null])]
    #[IsGranted('ROLE_USER')]
    public function export(EntryRepository $entryRepository, Member $member = null): StreamedResponse
    {
        if ($member) {
            $entries = $member->getEntries();
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
            'Remarque',
            'Date d\'ajout',
            'Participant'
        ];

        $sheet->fromArray($header);

        $row = 1;
        foreach ($entries as $entry) {
            $participant = $entry->getMember();
            $data = [
                $entry->getJid(),
                $entry->getCountry(),
                $entry->getPseudo(),
                $entry->getAge(),
                $entry->getRemark(),
                $entry->getCreatedAt()->format('d.m.Y H:i'),
                $participant->getLastName() . ' ' . $participant->getFirstName()
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
}
