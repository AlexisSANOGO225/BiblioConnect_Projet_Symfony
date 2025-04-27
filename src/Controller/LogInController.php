<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class LogInController extends AbstractController
{
    #[Route('/log/in', name: 'app_log_in')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        
        // Vérification du rôle de l'utilisateur après la connexion
        $user = $this->getUser();  // Récupère l'utilisateur connecté

        // Si l'utilisateur est authentifié et a un rôle spécifique
        if ($user) {
            // Vérification si l'utilisateur a le rôle ROLE_ADMIN
            if ($this->isGranted('ROLE_ADMIN')) {
                // Rediriger vers la page d'administration
                return $this->redirectToRoute('app_admin'); // Remplace 'admin_dashboard' par la route appropriée pour l'admin
            }
        }

        // Si aucun rôle n'est trouvé ou l'utilisateur est non connecté, rester sur la page de connexion
        return $this->render('log_in/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
}
