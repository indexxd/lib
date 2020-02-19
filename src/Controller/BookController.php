<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Book;
use App\Repository\BookRepository;
use App\Repository\ReservationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class BookController extends AbstractController
{
    /**
     * @Route("/book/{id}/reservation-controls", name="reservation_controls", methods={"GET"}, options={"expose":true})
     */
    public function reservationControls(Book $book, BookRepository $bookRep, ReservationRepository $resRep)
    {
        $available = $bookRep->isAvailable($book);
        $activeReservationExists = $resRep->activeReservationExists($book, $this->getUser());

        return $this->json(["available" => $available, "activeReservationExists" => $activeReservationExists]);
    }
    
    /**
     * @Route("/book/{slug}", name="book", methods={"GET"})
     */
    public function index($slug, BookRepository $repo)
    {        
        $book = $repo->findOneBy(["slug" => $slug]);

        if ($book === null) throw new NotFoundHttpException("Book not found.");        
        
        return $this->render('book/index.html.twig', [
            'book' => $book,
        ]);
    }

    /**
     * @Route("/book/{id}/reservation", name="delete_book_reservation", methods={"DELETE"}, options={"expose":true})
     */
    public function deleteBookReservation(Book $book, ReservationRepository $resRep)
    {        
        $resRep->deleteActiveReservation($book, $this->getUser());

        return new Response();
    }
}
