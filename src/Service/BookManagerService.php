<?php

namespace App\Service;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;

class BookManagerService
{
    private BookRepository $bookRepository;
    private AuthorRepository $authorRepository;

    public function __construct(BookRepository $bookRepository, AuthorRepository $authorRepository)
    {
        $this->bookRepository = $bookRepository;
        $this->authorRepository = $authorRepository;
    }

    /**
     * a) Compte le nombre de livres écrits par un auteur donné.
     */
    public function countBooksByAuthor(Author $author): int
    {
        // On suppose que la relation Author -> Books existe
        return count($author->getBooks());
    }

    /**
     * b) Retourne la liste des auteurs ayant publié plus de 3 livres.
     *
     * @return Author[]
     */
    public function bestAuthors(): array
    {
        $authors = $this->authorRepository->findAll();
        $bestAuthors = [];

        foreach ($authors as $author) {
            if ($this->countBooksByAuthor($author) > 3) {
                $bestAuthors[] = $author;
            }
        }

        return $bestAuthors;
    }
}
