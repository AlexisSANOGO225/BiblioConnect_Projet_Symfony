<?php

namespace App\Controller;

use App\Entity\LibraryStock;
use App\Form\LibraryStockType;
use App\Repository\LibraryStockRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/library/stock')]
final class LibraryStockController extends AbstractController
{
    #[Route(name: 'app_library_stock_index', methods: ['GET'])]
    public function index(LibraryStockRepository $libraryStockRepository): Response
    {
        return $this->render('library_stock/index.html.twig', [
            'library_stocks' => $libraryStockRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_library_stock_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $libraryStock = new LibraryStock();
        $form = $this->createForm(LibraryStockType::class, $libraryStock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($libraryStock);
            $entityManager->flush();

            return $this->redirectToRoute('app_library_stock_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('library_stock/new.html.twig', [
            'library_stock' => $libraryStock,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_library_stock_show', methods: ['GET'])]
    public function show(LibraryStock $libraryStock): Response
    {
        return $this->render('library_stock/show.html.twig', [
            'library_stock' => $libraryStock,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_library_stock_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LibraryStock $libraryStock, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LibraryStockType::class, $libraryStock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_library_stock_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('library_stock/edit.html.twig', [
            'library_stock' => $libraryStock,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_library_stock_delete', methods: ['POST'])]
    public function delete(Request $request, LibraryStock $libraryStock, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$libraryStock->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($libraryStock);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_library_stock_index', [], Response::HTTP_SEE_OTHER);
    }
}
