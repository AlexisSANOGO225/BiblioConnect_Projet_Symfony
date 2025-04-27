<?php

namespace App\Entity;

use App\Repository\AuthorsBooksRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuthorsBooksRepository::class)]
class AuthorsBooks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'authorsBooks')]
    private ?Authors $authors = null;

    #[ORM\ManyToOne(inversedBy: 'authorsBooks')]
    private ?Books $books = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthors(): ?Authors
    {
        return $this->authors;
    }

    public function setAuthors(?Authors $authors): static
    {
        $this->authors = $authors;

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
}
