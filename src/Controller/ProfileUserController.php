<?php

namespace App\Controller;

use App\Entity\Accounts;
use App\Repository\ReservationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfileUserController extends AbstractController
{
    #[Route('/profile/user', name: 'app_profile_user')]
    public function index(UserInterface $user, ReservationsRepository $reservationsRepository): Response
    {
        // Cast $user en type spécifique à votre entité (Accounts) pour avoir accès aux méthodes personnalisées
        /** @var Accounts $user */

        // Récupérer les informations de l'utilisateur connecté
        $username = $user->getUsername();

        // Récupérer les réservations de l'utilisateur connecté
        $reservations = $reservationsRepository->findBy(['accounts' => $user]);

        // Récupérer les détails de chaque réservation (titre du livre, prix, date de réservation)
        $reservationDetails = [];
        foreach ($reservations as $reservation) {
            $book = $reservation->getBooks();  // Récupérer l'entité Books liée à la réservation
            $bookInventory = $book->getBooksInventories()->first();  // Récupérer l'inventaire du livre (on prend le premier inventaire, si plusieurs)

            // Collecter les informations pour chaque réservation
            $reservationDetails[] = [
                'id' => $reservation->getId(),  // Ajout de l'ID de la réservation
                'bookTitle' => $book ? $book->getTitle() : 'Titre non disponible',
                'price' => $bookInventory ? $bookInventory->getPrice() : 'Prix non disponible',
                'createdAt' => $reservation->getCreatedAt() ? $reservation->getCreatedAt()->format('Y-m-d H:i:s') : 'Date non disponible'
            ];
            
        }

        // Passer ces données à la vue pour les afficher
        return $this->render('profile_user/index.html.twig', [
            'username' => $username,
            'reservationDetails' => $reservationDetails,  // Passer les détails des réservations à la vue
        ]);
    }

    #[Route('/profile/user/reservation/{id}/cancel', name: 'app_profile_user_cancel_reservation', methods: ['POST'])]
    public function cancelReservation(int $id, ReservationsRepository $reservationsRepository, EntityManagerInterface $entityManager): Response
    {
        // Trouver la réservation par son ID
        $reservation = $reservationsRepository->find($id);

        if (!$reservation) {
            // Si la réservation n'existe pas, rediriger vers la page avec un message d'erreur
            $this->addFlash('error', 'Réservation non trouvée.');
            return $this->redirectToRoute('app_profile_user');
        }

        // Supprimer la réservation
        $entityManager->remove($reservation);
        $entityManager->flush();

        // Ajouter un message de succès
        $this->addFlash('success', 'Réservation annulée avec succès.');

        // Rediriger l'utilisateur vers la page du profil avec la liste mise à jour
        return $this->redirectToRoute('app_profile_user');
    }
}
