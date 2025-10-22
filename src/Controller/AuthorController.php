<?php

namespace App\Controller;

use App\Entity\Author;
use App\Service\HappyQuote;

use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/ShowAllAuthor' , name:'ShowAllAuthor')]
    public function ShowAllAuthor(AuthorRepository $repo,HappyQuote $quote)
    {
        //new pour service//
        $bestQuote=$quote->getHappyMessage();
        ////
        $authors = $repo->findAll();
        return $this->render('author/listAuthor.html.twig', [
            'list' => $authors , 'thebest'=> $bestQuote
        ]);



    }
    #[Route('/authors/best', name: 'best_authors')]
    public function bestAuthors(BookManagerService $bookManagerService): Response
    {
        $bestAuthors = $bookManagerService->bestAuthors();

        return $this->render('author/best.html.twig', [
            'authors' => $bestAuthors,
        ]);
    }

    #[Route('/addAuthor' , name:'addAuthor')]
    public function add (Request $request, ManagerRegistry $doctrine): Response{
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $doctrine->getManager();
            $em->persist($author);
            $em->flush();
            return $this->redirectToRoute('ShowAllAuthor');
        }
        return $this->render('author/add.html.twig', [
            'formA' => $form->createView()
        ]);

    }

    #[Route('/editAuthor{id}' , name:'editAuthor')]
    public function edit (Author $author, Request $request, ManagerRegistry $doctrine, $id,AuthorRepository $repo) : Response{
        $author = $repo->find($id);

        if (!$author) {
            throw $this->createNotFoundException('Auteur non trouvé.');
        }
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('ShowAllAuthor');
        }
        return $this->render('author/edit.html.twig', [
            'formA' => $form->createView()
        ]);
    }

    #[Route('deleteAuthor{id}', name:'deleteAuthor')]
    public function delete(ManagerRegistry $doctrine, $id, AuthorRepository $repo){
        $author=$repo->find($id);
        $em=$doctrine->getManager();
        $em->remove($author);
        $em->flush();
        return $this->redirect('ShowAllAuthor');

    }

    
}