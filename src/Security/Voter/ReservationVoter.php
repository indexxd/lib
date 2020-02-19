<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Security;
use App\Entity\Reservation;

class ReservationVoter extends Voter
{
    const DELETE = "DELETE";
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    
    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [self::DELETE]) && $subject instanceof Reservation;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {        
        /** @var Reservation $subject */

        $user = $token->getUser();
               
        if ($attribute === self::DELETE) {
            if ($subject->getUser() === $user && $this->security->isGranted("ROLE_CUSTOMER")) {
                return true;
            }
        }

        return false;
    } 
    
}
