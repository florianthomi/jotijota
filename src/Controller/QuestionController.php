<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\Question1Type;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/question')]
class QuestionController extends AbstractController
{
    #[Route('/', name: 'app_question_index', methods: ['GET'])]
    public function index(QuestionRepository $questionRepository): Response
    {
        return $this->render('question/index.html.twig', [
            'entities' => $questionRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_question_show', methods: ['GET'])]
    public function show(Question $question): Response
    {
        return $this->render('question/show.html.twig', [
            'question' => $question,
        ]);
    }

    #[Route('/{id}', name: 'app_question_delete', methods: ['POST'])]
    public function delete(Request $request, Question $question, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($question);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_question_index', [], Response::HTTP_SEE_OTHER);
    }
}
