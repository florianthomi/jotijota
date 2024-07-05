<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, GroupRepository $groupRepository, TranslatorInterface $translator, Security $security, EntityManagerInterface $entityManager): Response
    {
        $groups = $groupRepository->getVisibleGroups()->getQuery()->getResult();

        if ($groups === []) {
            $this->addFlash('danger', $translator->trans('message.closed_subscriptions'));

            return $this->redirectToRoute('app_login');
        }

        $modifiedCoords = [];

        /** @var Group $group */
        foreach ($groups as $group) {
            $coords = $group->getCoords();
            $modifiedCoords[] = [...$coords->jsonSerialize(), 'title' => $group->getName()];
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $security->login($user, 'form_login', 'main');
        }

        return $this->render('registration/register.html.twig', [
            'groups' => $modifiedCoords,
            'registrationForm' => $form,
        ]);
    }
}
