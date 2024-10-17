<?php

namespace App\Controller;

use App\Entity\Group;
use App\Form\GroupType;
use App\Repository\GroupRepository;
use App\Security\Voter\AdminVoter;
use App\Security\Voter\GroupVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/group')]
#[IsGranted(attribute: GroupVoter::LIST)]
class GroupController extends AbstractController
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    #[Route('/', name: 'app_group_index', methods: ['GET'])]
    public function index(GroupRepository $groupRepository): Response
    {
        return $this->render('group/index.html.twig', [
            'entities' => $groupRepository->findGroupByUser($this->isGranted(AdminVoter::ROLE_SUPER_ADMIN) ? null : $this->getUser()->getId()),
        ]);
    }

    #[Route('/new', name: 'app_group_new', methods: ['GET', 'POST'])]
    #[IsGranted(attribute: GroupVoter::CREATE)]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $group = new Group();
        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($group->getCoordinators() as $coordinator) {
                if (null === $coordinator->getGroup()) {
                    $coordinator->setGroup($group);
                }
            }

            $entityManager->persist($group);
            $entityManager->flush();

            $this->addFlash('success', $this->translator->trans('message.success.element_added'));

            return $this->redirectToRoute('app_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('group/new.html.twig', [
            'group' => $group,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_group_show', methods: ['GET'])]
    #[IsGranted(attribute: GroupVoter::MANAGE, subject: 'group')]
    public function show(Group $group): Response
    {
        return $this->render('group/show.html.twig', [
            'group' => $group,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_group_edit', methods: ['GET', 'POST'])]
    #[IsGranted(attribute: GroupVoter::MANAGE, subject: 'group')]
    public function edit(Request $request, Group $group, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($group->getCoordinators() as $coordinator) {
                if (null === $coordinator->getGroup()) {
                    $coordinator->setGroup($group);
                }
            }

            $entityManager->flush();

            $this->addFlash('success', $this->translator->trans('message.success.element_updated'));

            return $this->redirectToRoute('app_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('group/edit.html.twig', [
            'group' => $group,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_group_delete', methods: ['POST'])]
    #[IsGranted(attribute: GroupVoter::MANAGE, subject: 'group')]
    public function delete(Request $request, Group $group, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$group->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($group);
            $entityManager->flush();

            $this->addFlash('success', $this->translator->trans('message.success.element_deleted'));
        }

        return $this->redirectToRoute('app_group_index', [], Response::HTTP_SEE_OTHER);
    }
}
