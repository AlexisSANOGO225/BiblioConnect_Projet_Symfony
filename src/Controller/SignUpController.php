<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\HttpFoundation\Request;
use App\Entity\Accounts;
use App\Form\SignUpType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


final class SignUpController extends AbstractController
{
    #[Route('/sign/up', name: 'app_sign_up')]
    public function index(UserPasswordHasherInterface $passwordHasher, Request $request, EntityManagerInterface $entityManager): Response
    {
        $account = new Accounts();
        $form = $this->createForm(SignUpType::class, $account);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $getPassword = $form->get("password")->getData();

            $hashedPassword = $passwordHasher->hashPassword(
                $account,
                $getPassword
            );
            $account->setRoles(array('ROLE_USER'));
            $account->setPassword($hashedPassword);

            $entityManager->persist($account);
            $entityManager->flush();

            return $this->redirectToRoute('app_log_in');
        }

        return $this->render('sign_up/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
