<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Book;
use App\Repository\BookRepository;
use App\Util\EntitySluggerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class BookFixtures extends Fixture
{
    const TITLE = 0;
    const TITLE_ORIGINAL = 1;
    const AUTHOR = 2;
    const DESCRIPTION = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.";
    const DATE = 8;


    private $books = [
        ["El astillero", "Big boss", "Dr Frankenstein", "en", "es", "translator1", "", 1, "23-05-2013"], 
        ["Birdsong", "La muerta", "Get Mullied", "es", "en", "translator1", "", 2, "23-05-2013"], 
        ["Bleak House", "kokoska", "Henry Ford", "cs", "en", "translator2", "", 5, "23-05-2010"], 
        ["Invitation to the Waltz", null, "Xena Warrior", "en", null, "translator3", "", 7, "23-05-2018"], 
        ["The Hunchback of Notre-Dame", "Oui Jean", "Elliot Alderson", "fr", "en", "translator4", "", 1, "23-05-2018"], 
        ["The Tartar Steppe", null, "John Hodgman", "en", null, "translator4", "", 1, "23-05-2015"], 
        ["Иди на хуй", "What up", "bookRepositoryandon Sanderson", "en", "ru", "translator5", "", 2, "23-05-2019"], 
        ["Harry Potter", null, "J. K. Rowling", "en", null, "translator9", "", 1, "23-05-2010"], 
    ];

    private $bookRepository;
    private $slugger;
   
    public function __construct(EntitySluggerInterface $slugger, BookRepository $bookRepository) {
        $this->slugger = $slugger;
        $this->bookRepository = $bookRepository;
    }
    
    public function load(ObjectManager $manager)
    {
        
        foreach ($this->books as $index => $data) {
            $book = new Book();
            $book->setTitle($data[self::TITLE]);
            $book->setOriginalTitle($data[self::TITLE_ORIGINAL]);
            $book->setAuthor($data[self::AUTHOR]);
            $book->setReleased(\DateTime::createFromFormat('d-m-Y', $data[self::DATE]));
            $book->setDescription(self::DESCRIPTION);
            $book->setCover("/build/images/book-cover.jpg");
            $slug = $this->slugger->getUnique($book, "title");
            $book->setSlug($slug);
            
            $this->setReference($index, $book);

            $manager->persist($book);
        }
        
        $manager->flush();
    }

}
