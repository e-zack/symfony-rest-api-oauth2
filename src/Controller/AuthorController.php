<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Author controller
 * @Route("/api", name="api_")
 */
class AuthorController extends AbstractFOSRestController
{
    /**
     * Lists all authors
     * @Rest\Get("/authors")
     * 
     * @return Response
     */
    public function getAuthorAction()
    {
        $repository = $this->getDoctrine()->getRepository(Author::class);
        $authors = $repository->findall();
        return $this->handleView($this->view($authors));
    }

    /**
     * Create author
     * @Rest\Post("/author")
     * 
     * @return Response
     */
    public function postAuthorAction(Request $request)
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($author);
            $em->flush();
            return $this->handleView($this->view(['status'=>'ok'], Response::HTTP_CREATED));
        }
        return $this->handleView($this->view($form->getErrors()));
    }
}
