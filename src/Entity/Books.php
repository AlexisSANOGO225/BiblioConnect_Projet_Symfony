<?php

namespace App\Entity;

use App\Repository\BooksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BooksRepository::class)]
class Books
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $theme = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $publicationDate = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    private ?Categories $categories = null;

    /**
     * @var Collection<int, Reservations>
     */
    #[ORM\OneToMany(targetEntity: Reservations::class, mappedBy: 'books')]
    private Collection $reservations;

    /**
     * @var Collection<int, Reviews>
     */
    #[ORM\OneToMany(targetEntity: Reviews::class, mappedBy: 'books')]
    private Collection $reviews;

    /**
     * @var Collection<int, AuthorsBooks>
     */
    #[ORM\OneToMany(targetEntity: AuthorsBooks::class, mappedBy: 'books')]
    private Collection $authorsBooks;

    /**
     * @var Collection<int, BooksInventory>
     */
    #[ORM\OneToMany(targetEntity: BooksInventory::class, mappedBy: 'books')]
    private Collection $booksInventories;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->authorsBooks = new ArrayCollection();
        $this->booksInventories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(string $theme): static
    {
        $this->theme = $theme;

        return $this;
    }

    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(?\DateTimeInterface $publicationDate): static
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    public function getCategories(): ?Categories
    {
        return $this->categories;
    }

    public function setCategories(?Categories $categories): static
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return Collection<int, Reservations>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservations $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setBooks($this);
        }

        return $this;
    }

    public function removeReservation(Reservations $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getBooks() === $this) {
                $reservation->setBooks(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reviews>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Reviews $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setBooks($this);
        }

        return $this;
    }

    public function removeReview(Reviews $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getBooks() === $this) {
                $review->setBooks(null);
            }
        }

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
            $authorsBook->setBooks($this);
        }

        return $this;
    }

    public function removeAuthorsBook(AuthorsBooks $authorsBook): static
    {
        if ($this->authorsBooks->removeElement($authorsBook)) {
            // set the owning side to null (unless already changed)
            if ($authorsBook->getBooks() === $this) {
                $authorsBook->setBooks(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, BooksInventory>
     */
    public function getBooksInventories(): Collection
    {
        return $this->booksInventories;
    }

    public function addBooksInventory(BooksInventory $booksInventory): static
    {
        if (!$this->booksInventories->contains($booksInventory)) {
            $this->booksInventories->add($booksInventory);
            $booksInventory->setBooks($this);
        }

        return $this;
    }

    public function removeBooksInventory(BooksInventory $booksInventory): static
    {
        if ($this->booksInventories->removeElement($booksInventory)) {
            // set the owning side to null (unless already changed)
            if ($booksInventory->getBooks() === $this) {
                $booksInventory->setBooks(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
