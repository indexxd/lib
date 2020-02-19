<?php

namespace App\EntityListener;

use App\Entity\Book;
use App\Util\EntitySluggerInterface;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class BookListener
{
    private $slugger;
    
    public function __construct(EntitySluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
    
    /**
     * Generates a new slug when title is changed
     */
    public function preUpdate(Book $book, PreUpdateEventArgs $args)
    {
        $updates = $args->getEntityChangeSet();
        
        if (array_key_exists("title", $updates)) {
            $book->setSlug($this->slugger->getUnique($book, "title"));
        }
    }

}