<?php

namespace App\Controller;

use App\Repository\LoanRepository;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="account")
     */
    public function index()
    {
        return $this->render('account/index.html.twig', [

        ]);
    }

    /**
     * @Route("/account/reservations", name="account_reservations")
     */
    public function showReservations(ReservationRepository $resRep) 
    {
        $reservations = $resRep->findBy(["user" => $this->getUser(), "active" => true]);

        return $this->render("account/reservations.html.twig", [
            "reservations" => $reservations
        ]);
    }


    /**
     * @Route("/account/loans", name="account_loans")
     */
    public function showLoans(LoanRepository $loanRep)
    {
        $loans = $loanRep->findBy(["user" => $this->getUser(), "returned" => false]);

        return $this->render("account/loans.html.twig", [
            "loans" => $loans,
        ]);
    }
}
