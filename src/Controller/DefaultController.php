<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Book;
use App\Repository\BookRepository;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index()
    {              
        if ($this->isGranted("ROLE_ADMIN")) {
            return $this->redirectToRoute("admin_books");
        }
        
        return $this->render('default/index.html.twig');
    }


    /**
     * @Route("/search_results", name="search_results", methods={"POST"}, options={"expose":true})
     */
    public function searchResults(Request $request, BookRepository $repository)
    {
        $data = json_decode($request->getContent());
                
        $queryResult = $repository->findAnyBooks($data->value);

        return $this->json($queryResult);
    }
}
