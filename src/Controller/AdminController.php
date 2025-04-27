<?php

namespace App\Controller;

use App\Repository\AccountsRepository;
use App\Repository\AuthorsRepository;
use App\Repository\BooksRepository;
use App\Repository\CategoriesRepository;
use App\Repository\LanguagesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ReservationsRepository;
use App\Repository\ReviewsRepository;

final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(
        ReservationsRepository $reservationsRepository,
        BooksRepository $bookRepository,
        AuthorsRepository $authorRepository,
        ReviewsRepository $reviewsRepository,
        AccountsRepository $accountsRepository,
        CategoriesRepository $categoryRepository,
        LanguagesRepository $languageRepository): Response
    {
        // On récupère toutes les réservations avec jointure sur account et book
        $reservations = $reservationsRepository->findAllWithDetails();

        // Compter le nombre d'éléments
        $bookCount = $bookRepository->count([]);
        $authorCount = $authorRepository->count([]);
        $categoryCount = $categoryRepository->count([]);
        $languageCount = $languageRepository->count([]);
        $reviewCount = $reviewsRepository->count([]);
        $accountCount = $accountsRepository->count([]);


        return $this->render('admin/index.html.twig', [
            'reservations' => $reservations,
            'bookCount' => $bookCount,
            'authorCount' => $authorCount,
            'categoryCount' => $categoryCount,
            'languageCount' => $languageCount,
            'reviewCount' => $reviewCount,
            'accountCount' => $accountCount,
        ]);
    }
}
