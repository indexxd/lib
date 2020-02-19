<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BookRepository;
use App\Repository\ReservationRepository;
use App\Entity\Reservation;
use App\Entity\Book;
use App\Entity\User;
use App\Security\Voter\ReservationVoter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ReservationController extends AbstractController
{
    /**
     * @Route("/reservation/{id}", name="create_reservation", methods={"POST"}, options={"expose": true})
     * @IsGranted("ROLE_CUSTOMER")
     */
    public function create(Book $book, BookRepository $bookRep, ReservationRepository $resRep)
    {                
        $reservationExists = $resRep->activeReservationExists($book, $this->getUser());
        $available = $bookRep->isAvailable($book);

        if ($available && !$reservationExists) {
            $reservation = new Reservation($this->getUser(), $book);

            $em = $this->getDoctrine()->getManager();
            $em->persist($reservation);
            $em->flush();

            return new Response("", Response::HTTP_CREATED);
        }
      
        return new Response();
    }

    /**
     * @Route("/reservation/{id}", name="delete_reservation", methods={"DELETE"}, options={"expose": true})
     * @IsGranted("DELETE", subject="reservation")
     */
    public function delete(Reservation $reservation)
    {
        $this->getDoctrine()->getManager()->remove($reservation)->flush();
                
        return new Response();
    }

}
