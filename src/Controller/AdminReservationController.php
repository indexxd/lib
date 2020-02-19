<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminReservationController extends AbstractController
{
    /**
     * @Route("/admin/reservations", name="admin_reservations")
     */
    public function index(ReservationRepository $resRep)
    {
        $pendingReservations = $resRep->findBy(["active" => true, "ready" => false]);
        $availableReservations = $resRep->findBy(["active" => true, "ready" => true]);
        
        return $this->render('admin/reservations.html.twig', [
            "pendingReservations" => $pendingReservations,
            "availableReservations" => $availableReservations,
        ]);
    }


    /**
     * @Route("/admin/reservation/{id}/toggle-ready", name="toggle_reservation_ready", methods={"PATCH"}, options={"expose"=true})
     */
    public function toggleReady(Reservation $reservation)
    {
        $reservation->setReady(!$reservation->getReady());
        $this->getDoctrine()->getManager()->flush();

        return new Response();        
    }
    
    
}
