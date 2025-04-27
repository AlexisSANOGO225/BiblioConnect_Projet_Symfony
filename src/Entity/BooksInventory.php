<?php

namespace App\Entity;

use App\Repository\BooksInventoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BooksInventoryRepository::class)]
class BooksInventory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'booksInventories')]
    private ?LibraryStock $librarystock = null;

    #[ORM\ManyToOne(inversedBy: 'booksInventories')]
    private ?Languages $languages = null;

    #[ORM\ManyToOne(inversedBy: 'booksInventories')]
    private ?Books $books = null;

    #[ORM\Column]
    private ?int $availableCopie = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 7, scale: 2)]
    private ?string $price = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibrarystock(): ?LibraryStock
    {
        return $this->librarystock;
    }

    public function setLibrarystock(?LibraryStock $librarystock): static
    {
        $this->librarystock = $librarystock;

        return $this;
    }

    public function getLanguages(): ?Languages
    {
        return $this->languages;
    }

    public function setLanguages(?Languages $languages): static
    {
        $this->languages = $languages;

        return $this;
    }

    public function getBooks(): ?Books
    {
        return $this->books;
    }

    public function setBooks(?Books $books): static
    {
        $this->books = $books;

        return $this;
    }

    public function getAvailableCopie(): ?int
    {
        return $this->availableCopie;
    }

    public function setAvailableCopie(int $availableCopie): static
    {
        $this->availableCopie = $availableCopie;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }
}
