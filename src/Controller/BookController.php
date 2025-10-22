<?php

namespace App\Controller;
use App\Repository\BookRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType; // nécessaire pour le champ 'ref'

use App\Entity\Book;
use App\Form\BookType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BookController extends AbstractController
{

    #[Route('/addBook', name:"addBook")]
    public function add(Request $request, ManagerRegistry $doctrine){
        $book=new Book();
        $form=$this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $book->setPublished(true);
            $author = $book->getAuthor();
            $author->setNbBooks($author->getNbBooks() + 1);

            $em=$doctrine->getManager();
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('ShowAllBook');
        }

            return $this->render('book/add.html.twig', ['formB' => $form->createView()]);
    }

    #[Route('/book', name: 'ShowAllBook')]
    public function list(ManagerRegistry $doctrine): Response
    {
        $repo = $doctrine->getRepository(\App\Entity\Book::class);

        $publishedBooks = $repo->findBy(['published' => true]);

        $countPublished = count($repo->findBy(['published' => true]));
        $countUnpublished = count($repo->findBy(['published' => false]));

        return $this->render('book/list.html.twig', [
            'books' => $publishedBooks,
            'countPublished' => $countPublished,
            'countUnpublished' => $countUnpublished,
        ]);
    }

#[Route('/editBook/{id}', name:'editBook')]

public function edit(Request $request, ManagerRegistry $doctrine, int $id, BookRepository $repo): Response
{
    $book = $repo->find($id);

    if (!$book) {
        throw $this->createNotFoundException('Livre non trouvé.');
    }

    $form = $this->createForm(BookType::class, $book);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em = $doctrine->getManager();
        $em->flush();

        return $this->redirectToRoute('ShowAllBook');
    }

    return $this->render('book/edit.html.twig', [
        'formA' => $form->createView(),
    ]);
}

#[Route('deleteBook{id}', name:'deleteBook')]
    public function delete(ManagerRegistry $doctrine, $id, BookRepository $repo){
        $book=$repo->find($id);
        $em=$doctrine->getManager();
        $em->remove($book);
        $em->flush();
        return $this->redirect('ShowAllBook');

    }

     #[Route('/ShowBook/{id}', name: 'detailBook')]

    public function showBook($id, BookRepository $repository)
    {
        $book = $repository->find($id);
        if (!$book) {
            return $this->redirectToRoute('ShowAllBook');
        }

        return $this->render('book/show.html.twig', ['b' => $book]);

    
}
#[Route('/book/search', name: 'searchBook')]
public function search(Request $request, BookRepository $bookRepository): Response
{
    $form = $this->createFormBuilder()
        ->add('ref', TextType::class)
        ->getForm();

    $form->handleRequest($request);
    $book = null;

    if ($form->isSubmitted() && $form->isValid()) {
        $ref = $form->getData()['ref'];
        $book = $bookRepository->searchBookByRef($ref);
    }

    return $this->render('book/search.html.twig', [
        'form' => $form->createView(),
        'book' => $book,
        
    ]);
}
}