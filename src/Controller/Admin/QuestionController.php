<?php

namespace App\Controller\Admin;

use App\Entity\Question;
use App\Form\QuestionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/admin/question")]
class QuestionController extends AdminController
{
    #[Route( "/", name: "admin_question_list", methods: [ "GET" ] )]
    public function list(): Response {
        $questionToCheckList = $this->getDoctrine()->getManager()
            ->getRepository( Question::class )->findBy(["status" => false]);
        $questionCheckedList = $this->getDoctrine()->getManager()
            ->getRepository( Question::class )->findBy(["status" => true]);

        return $this->render( "admin/question/list.html.twig", [
            "questionToCheckList" => $questionToCheckList,
            "questionCheckedList" => $questionCheckedList,
        ] );
    }

    #[Route( "/add", name: "admin_question_add", methods: [ "GET", "POST" ] )]
    public function add(Request $request): Response {
        // On crée une instance de Question avec 4 qui demande 4 réponses
        $question = (new Question())
            ->requiredResponse(4)
            ->setUser($this->getUser())
        ;

        $form = $this->createForm( QuestionType::class, $question );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist( $question );
            $entityManager->flush();

            return $this->redirectToRoute( "admin_question_list", [], Response::HTTP_SEE_OTHER );
        }

        return $this->renderForm( "admin/question/add.html.twig", [
            "form" => $form,
        ] );
    }

    #[Route( "/{question}/edit", name: "admin_question_edit", methods: [ "GET", "POST" ] )]
    public function edit(Request $request, Question $question): Response {
        $form = $this->createForm( QuestionType::class, $question );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute( "admin_question_list", [], Response::HTTP_SEE_OTHER );
        }

        return $this->renderForm( "admin/question/edit.html.twig", [
            "question" => $question,
            "form" => $form,
        ] );
    }

    #[Route( "/{question}", name: "admin_question_delete", methods: [ "POST" ])]
    public function delete(Request $request, Question $question): Response
    {
        if ( $this->isCsrfTokenValid( "delete" . $question->getId(), $request->get( "_token" ) ) ) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove( $question );
            $entityManager->flush();
        }
        return $this->redirectToRoute( "admin_question_list", [], Response::HTTP_SEE_OTHER );
    }
}