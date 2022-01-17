<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/question")]
class QuestionController extends AbstractController
{
    #[Route( "/submit", name: "app_question_add", methods: [ "GET", "POST" ] )]
    public function submit(Request $request): Response
    {
        $question = new Question();

        $form = $this->createForm( QuestionType::class, $question );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist( $question );
            $entityManager->flush();

            return $this->redirectToRoute( "admin_question_list", [], Response::HTTP_SEE_OTHER );
        }

        return $this->renderForm( "question/submit.html.twig", [
            "form" => $form,
        ] );
    }


    #[Route( "/random", name: "app_question_random", methods: [ "POST" ] )]
    public function random(Category $category): JsonResponse {
        return $this->json([]);
    }

    #[Route( "/answer", name: "app_question_answer", methods: [ "POST" ] )]
    public function answer(Question $question): JsonResponse {
        return $this->json([]);
    }
}
