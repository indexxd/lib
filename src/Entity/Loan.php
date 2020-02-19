<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LoanRepository")
 */
class Loan
{
    const INITIAL_DAYS_PERIOD = 30;
    const EXTENSION_DAYS_PERIOD = 14;
    const PENALTY_PER_DAY = 10;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $dateCreated;

    /**
     * @ORM\Column(type="date")
     */
    private $dueBy;

    /**
     * @ORM\Column(type="boolean")
     */
    private $returned;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="loans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Book")
     * @ORM\JoinColumn(nullable=false)
     */
    private $book;


    public function __construct()
    {
        $this->setDateCreated(new \DateTime());
        $this->setReturned(false);
    }
    
    public function calculatePenalty(): int
    {
        if ($this->getDaysLeft() > 0) {
            return 0;
        }

        return self::PENALTY_PER_DAY * $this->getDaysLeft() * -1;
    }
    
    public function getDaysLeft()
    {
        $diff = date_diff( new \DateTime(), $this->getDueBy(), false);
        return $diff->invert ? $diff->days * -1 : $diff->days;
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getDueBy(): ?\DateTimeInterface
    {
        return $this->dueBy;
    }

    public function setDueBy(\DateTimeInterface $dueBy): self
    {
        $this->dueBy = $dueBy;

        return $this;
    }

    public function getReturned(): ?bool
    {
        return $this->returned;
    }

    public function setReturned(bool $returned): self
    {
        $this->returned = $returned;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): self
    {
        $this->book = $book;

        return $this;
    }
}
