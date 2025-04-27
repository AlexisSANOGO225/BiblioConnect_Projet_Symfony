<?php

namespace App\Controller;

use App\Entity\AuthorsBooks;
use App\Form\AuthorsBooksType;
use App\Repository\AuthorsBooksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/authors/books')]
final class AuthorsBooksController extends AbstractController
{
    #[Route(name: 'app_authors_books_index', methods: ['GET'])]
    public function index(AuthorsBooksRepository $authorsBooksRepository): Response
    {
        return $this->render('authors_books/index.html.twig', [
            'authors_books' => $authorsBooksRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_authors_books_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $authorsBook = new AuthorsBooks();
        $form = $this->createForm(AuthorsBooksType::class, $authorsBook);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($authorsBook);
            $entityManager->flush();

            return $this->redirectToRoute('app_authors_books_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('authors_books/new.html.twig', [
            'authors_book' => $authorsBook,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_authors_books_show', methods: ['GET'])]
    public function show(AuthorsBooks $authorsBook): Response
    {
        return $this->render('authors_books/show.html.twig', [
            'authors_book' => $authorsBook,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_authors_books_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AuthorsBooks $authorsBook, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AuthorsBooksType::class, $authorsBook);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_authors_books_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('authors_books/edit.html.twig', [
            'authors_book' => $authorsBook,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_authors_books_delete', methods: ['POST'])]
    public function delete(Request $request, AuthorsBooks $authorsBook, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$authorsBook->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($authorsBook);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_authors_books_index', [], Response::HTTP_SEE_OTHER);
    }
}
