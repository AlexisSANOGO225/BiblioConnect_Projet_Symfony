<?php

namespace App\Entity;

use App\Repository\AuthorsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuthorsRepository::class)]
class Authors
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column]
    private ?int $booksCount = null;

    /**
     * @var Collection<int, AuthorsBooks>
     */
    #[ORM\OneToMany(targetEntity: AuthorsBooks::class, mappedBy: 'authors')]
    private Collection $authorsBooks;

    public function __construct()
    {
        $this->authorsBooks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getBooksCount(): ?int
    {
        return $this->booksCount;
    }

    public function setBooksCount(int $booksCount): static
    {
        $this->booksCount = $booksCount;

        return $this;
    }

    /**
     * @return Collection<int, AuthorsBooks>
     */
    public function getAuthorsBooks(): Collection
    {
        return $this->authorsBooks;
    }

    public function addAuthorsBook(AuthorsBooks $authorsBook): static
    {
        if (!$this->authorsBooks->contains($authorsBook)) {
            $this->authorsBooks->add($authorsBook);
            $authorsBook->setAuthors($this);
        }

        return $this;
    }

    public function removeAuthorsBook(AuthorsBooks $authorsBook): static
    {
        if ($this->authorsBooks->removeElement($authorsBook)) {
            // set the owning side to null (unless already changed)
            if ($authorsBook->getAuthors() === $this) {
                $authorsBook->setAuthors(null);
            }
        }

        return $this;
    }
}
