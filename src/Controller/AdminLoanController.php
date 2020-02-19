<?php

namespace App\Controller;

use App\Entity\Loan;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Reservation;
use App\Repository\LoanRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminLoanController extends AbstractController
{
    /**
     * @Route("/admin/loans", name="admin_loans")
     */
    public function index(LoanRepository $loanRep)
    {
        $loans = $loanRep->findNotReturnedSorted();
        
        return $this->render('admin/loans.html.twig', [
            "loans" => $loans,
        ]);
    }


    /**
     * @Route("admin/loan/{id}/return", name="return_book", methods={"PATCH"}, options={"expose"=true})
     */
    public function returnBook(Loan $loan)
    {          
        $loan->setReturned(true);
        $loan->getBook()->increaseCurrentQuantityByOne();

        $this->getDoctrine()->getManager()->flush();

        return new Response();
    }

    /**
     * @Route("/admin/reservations/{id}/loan", name="create_loan_from_reservation", methods={"POST"}, options={"expose"=true})
     */
    public function createLoanFromReservation(Reservation $reservation)
    {
        $loan = new Loan();
        $loan
            ->setBook($reservation->getBook())
            ->setUser($reservation->getUser())
            ->setDueBy((new \DateTime($loan->getDateCreated()->format("d-m-Y")))->modify("+ " . Loan::INITIAL_DAYS_PERIOD . " Days"))
        ;
        
        $reservation->getBook()->decreaseCurrentQuantityByOne();
        $reservation->setActive(false);

        $em = $this->getDoctrine()->getManager();
        
        $em->persist($loan);
        $em->flush();

        return new Response("", Response::HTTP_CREATED);
    }

}