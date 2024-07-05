<?php

namespace App\Controller;

use App\Entity\Entry;
use App\Form\EntryType;
use App\Repository\EntryRepository;
use App\Security\Voter\EntryVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/entry')]
class EntryController extends AbstractController
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    #[Route('/', name: 'app_entry_index', methods: ['GET'])]
    public function index(EntryRepository $entryRepository): Response
    {
        return $this->render('entry/index.html.twig', [
            'entries' => $entryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_entry_new', methods: ['GET', 'POST'])]
    #[IsGranted(attribute: EntryVoter::NEW)]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $entry = new Entry();
        $entry->setUser($this->getUser());
        $entry->setEdition($this->getUser()?->getGroup()?->getCurrentEdition());
        $entry->initAnswers();

        $form = $this->createForm(EntryType::class, $entry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($entry);
            $entityManager->flush();

            $this->addFlash('success', $this->translator->trans('message.success.element_added'));

            return $this->redirectToRoute('app_entry_new', [], Response::HTTP_SEE_OTHER);
        }

        $entries = $entityManager->getRepository(Entry::class)->getEntriesByUserAndEdition($this->getUser()->getId());

        return $this->render('entry/new.html.twig', [
            'entries' => $entries,
            'stats' => array_reduce($entries, static function(array $acc, Entry $entry)
            {
                array_key_exists($entry->getCountry(), $acc) ? $acc[$entry->getCountry()] += 1 : $acc[$entry->getCountry()] = 1;
                return $acc;
            }, []),
            'entry' => $entry,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_entry_show', methods: ['GET'])]
    public function show(Entry $entry): Response
    {
        return $this->render('entry/show.html.twig', [
            'entry' => $entry,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_entry_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Entry $entry, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EntryType::class, $entry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_entry_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('entry/edit.html.twig', [
            'entry' => $entry,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_entry_delete', methods: ['POST'])]
    public function delete(Request $request, Entry $entry, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entry->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($entry);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_entry_index', [], Response::HTTP_SEE_OTHER);
    }
}
