<?php

namespace App\Controller;

use App\Entity\BooksInventory;
use App\Form\BooksInventoryType;
use App\Repository\BooksInventoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/books/inventory')]
final class BooksInventoryController extends AbstractController
{
    #[Route(name: 'app_books_inventory_index', methods: ['GET'])]
    public function index(BooksInventoryRepository $booksInventoryRepository): Response
    {
        return $this->render('books_inventory/index.html.twig', [
            'books_inventories' => $booksInventoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_books_inventory_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $booksInventory = new BooksInventory();
        $form = $this->createForm(BooksInventoryType::class, $booksInventory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($booksInventory);
            $entityManager->flush();

            return $this->redirectToRoute('app_books_inventory_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('books_inventory/new.html.twig', [
            'books_inventory' => $booksInventory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_books_inventory_show', methods: ['GET'])]
    public function show(BooksInventory $booksInventory): Response
    {
        return $this->render('books_inventory/show.html.twig', [
            'books_inventory' => $booksInventory,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_books_inventory_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BooksInventory $booksInventory, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BooksInventoryType::class, $booksInventory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_books_inventory_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('books_inventory/edit.html.twig', [
            'books_inventory' => $booksInventory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_books_inventory_delete', methods: ['POST'])]
    public function delete(Request $request, BooksInventory $booksInventory, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$booksInventory->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($booksInventory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_books_inventory_index', [], Response::HTTP_SEE_OTHER);
    }
}
