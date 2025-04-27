<?php

namespace App\Controller;

use App\Repository\BooksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function lastBooks(BooksRepository $booksRepository): Response
    {
        // On récupère les livres avec leurs inventaires
        $books = $booksRepository->createQueryBuilder('b')
            ->leftJoin('b.booksInventories', 'bi')
            ->addSelect('bi')
            ->orderBy('b.id', 'DESC')
            ->setMaxResults(6)
            ->getQuery()
            ->getResult();

        // On ajoute le prix du premier inventaire à chaque livre
        foreach ($books as $book) {
            // Vérifie si le livre a des inventaires associés
            if ($book->getBooksInventories()->count() > 0) {
                // Récupère le prix du premier inventaire
                $book->firstPrice = $book->getBooksInventories()->first()->getPrice();
            } else {
                // Si pas d'inventaire, définit un prix par défaut ou laisse null
                $book->firstPrice = null;
            }
        }

        return $this->render('home/index.html.twig', [
            'books' => $books,
        ]);
    }
}
