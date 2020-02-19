<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use App\Util\EntitySluggerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin/books")
 */
class AdminBookController extends AbstractController
{
    const PAGE_LIMIT = 3;

    /**
     * @Route("", name="admin_books")
     */
    public function index(BookRepository $bookRep, Request $request, PaginatorInterface $paginator)
    {
        $page = $request->query->getInt("page", 1);
        $searchQuery = $request->get("search", "");

        $qb = $bookRep->searchWithQueryBuilder($searchQuery);

        $pagination = $paginator->paginate( $qb, $page, self::PAGE_LIMIT );
        
        
        return $this->render('admin_book/index.html.twig', [
            "pagination" => $pagination,
        ]);
    }


    /**
     * @Route("/{id}", name="admin_books_delete", methods={"DELETE"}, options={"expose"=true})
     */
    public function delete(Book $book)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($book);
        $em->flush();

        $this->addFlash("success", $book->getTitle() . " has been deleted.");

        return new Response();
    }


    /**
     * @Route("/{id}/edit", name="admin_books_edit")
     */
    public function edit(Book $book, Request $request)
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('cover')->getData();
            $imgPath = $this->isImageValid($file);

            if ($imgPath) {
                $book->setCover($imgPath);
            }

            
            $this->getDoctrine()->getManager()->flush();
            
            $this->addFlash("success", $book->getTitle() . " has been edited.");
            
            return $this->redirectToRoute("admin_books");
        }
        
        return $this->render("admin_book/edit.html.twig", [
            "form" => $form->createView(),
        ]);
    }


    /**
     * @Route("/new", name="admin_books_new")
     */
    public function new(Request $request, EntitySluggerInterface $slugger)
    {
        $book = new Book();
        
        $form = $this->createForm(BookType::class, $book)->add("submit", SubmitType::class, ["label" => "Create"]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $book->setSlug($slugger->getUnique($book, "title"));

            $file = $form->get('cover')->getData();
            $imgPath = $this->isImageValid($file);

            if ($imgPath) {
                $book->setCover($imgPath);
            }
            
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute("admin_books");
        }

        return $this->render("admin_book/edit.html.twig", [
            "form" => $form->createView(),
        ]);
    }

    /** Returns file name or false on error  */
    private function isImageValid(?UploadedFile $file)
    {
        if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('covers_directory'),
                        $newFilename
                    );
                } 
                catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    // return false;
                    throw $e;
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                return $newFilename;
            }

        return false;
    }
}
