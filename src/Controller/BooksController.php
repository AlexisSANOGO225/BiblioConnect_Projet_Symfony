<?php

namespace App\Controller;

use App\Entity\Books;
use App\Entity\Reservations;
use App\Entity\Reviews;
use App\Form\BooksType;
use App\Form\ReviewsType;
use App\Repository\BooksRepository;
use App\Repository\ReviewsRepository;
use App\Repository\BooksInventoryRepository;
use App\Repository\ReservationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Security;

#[Route('/books')]
final class BooksController extends AbstractController
{
    #[Route(name: 'app_books_index', methods: ['GET'])]
    public function index(BooksRepository $booksRepository, Request $request): Response
    {

        $searchTerm = $request->query->get('search');

        if ($searchTerm) {
            $books = $booksRepository->search($searchTerm);
        } else {
            $books = $booksRepository->findAll();
        }

        // On ajoute le prix du premier inventaire à chaque livre
        foreach ($books as $book) {
            if ($book->getBooksInventories()->count() > 0) {
                $book->firstPrice = $book->getBooksInventories()->first()->getPrice();
            } else {
                $book->firstPrice = null;
            }
        }

        return $this->render('books/index.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/new', name: 'app_books_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $book = new Books();
        $form = $this->createForm(BooksType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persister l'entité book
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('app_books_index');
        }

        return $this->render('books/new.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_books_show', methods: ['GET', 'POST'])]
    public function show(
        Books $book,
        Request $request,
        EntityManagerInterface $entityManager,
        ReviewsRepository $reviewsRepository,
        BooksInventoryRepository $booksInventoryRepository
    ): Response {
        // Récupérer les avis associés au livre, triés par date de création (du plus récent au plus ancien)
        $reviews = $reviewsRepository->findBy(
            ['books' => $book],
            ['createdAt' => 'DESC']
        );

        $categoryTitle = $book->getCategories() ? $book->getCategories()->getTitle() : 'Pas de catégorie';


        // Récupérer l'inventaire du livre pour obtenir le prix
        $bookInventory = $booksInventoryRepository->findOneBy(['books' => $book]);
        $price = $bookInventory ? $bookInventory->getPrice() : 'Pas de prix disponible';

        // Récupérer les auteurs associés au livre via l'entité AuthorsBooks
        $authorsBooks = $book->getAuthorsBooks();
        $authorFirstName = null;
        $authorLastName = null;

        if ($authorsBooks->count() > 0) {
            // Récupérer le prénom du premier auteur
            $authorFirstName = $authorsBooks->first()->getAuthors()->getFirstName();
            $authorLastName = $authorsBooks->first()->getAuthors()->getLastName();
        }

        // Créer un nouvel avis
        $review = new Reviews();
        $reviewForm = $this->createForm(ReviewsType::class, $review);
        $reviewForm->handleRequest($request);

        if ($reviewForm->isSubmitted() && $reviewForm->isValid()) {
            // Lier l'avis au livre et à l'utilisateur connecté
            $review->setBooks($book);
            $review->setAccounts($this->getUser()); // L'utilisateur connecté
            $review->setCreatedAt(new \DateTimeImmutable()); // Définir la date de création
            $entityManager->persist($review);
            $entityManager->flush();

            // Rediriger vers la même page pour voir le nouvel avis ajouté
            return $this->redirectToRoute('app_books_show', ['id' => $book->getId()]);
        }

        // Passer le formulaire à la vue
        return $this->render('books/show.html.twig', [
            'book' => $book,
            'reviews' => $reviews,
            'reviewForm' => $reviewForm->createView(),
            'price' => $price,
            'authorFirstName' => $authorFirstName,
            'authorLastName' => $authorLastName,
            'categoryTitle' => $categoryTitle,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_books_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Books $book, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BooksType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarder les modifications
            $entityManager->flush();

            return $this->redirectToRoute('app_books_index');
        }

        return $this->render('books/edit.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'app_books_delete', methods: ['POST'])]
    public function delete(Request $request, Books $book, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $book->getId(), $request->get('_token'))) {
            $entityManager->remove($book);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_books_index');
    }

    #[Route('/book/{id}/reserve', name: 'app_book_reserve', methods: ['POST'])]
    public function reserve(Books $book, ReservationsRepository $reservationsRepository, EntityManagerInterface $entityManager): Response
{
    // Récupérer l'utilisateur connecté directement via getUser() (méthode d'AbstractController)
    $user = $this->getUser();

    if (!$user) {
        // Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
        return $this->redirectToRoute('app_login');
    }

    // Vérifier si l'utilisateur a déjà réservé ce livre
    $existingReservation = $reservationsRepository->findOneBy([
        'accounts' => $user, // On vérifie la réservation de l'utilisateur
        'books' => $book
    ]);

    if ($existingReservation) {
        $this->addFlash('error', 'Vous avez déjà réservé ce livre.');
        return $this->redirectToRoute('app_home');
    }

    // Créer une nouvelle réservation
    $reservation = new Reservations();
    $reservation->setAccounts($user);  // Assigner l'utilisateur connecté à la réservation
    $reservation->setBooks($book);     // Assigner le livre réservé
    $reservation->setCreatedAt(new \DateTimeImmutable()); // Date de réservation

    // Sauvegarder la réservation dans la base de données
    $entityManager->persist($reservation);
    $entityManager->flush();

    // Ajouter un message flash pour confirmer la réservation
    $this->addFlash('success', 'Réservation réussie!');

    // Rediriger l'utilisateur vers la page du livre réservé
    return $this->redirectToRoute('app_home');
}

    
    
}
