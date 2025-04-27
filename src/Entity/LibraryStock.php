<?php

namespace App\Entity;

use App\Repository\LibraryStockRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LibraryStockRepository::class)]
class LibraryStock
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    /**
     * @var Collection<int, BooksInventory>
     */
    #[ORM\OneToMany(targetEntity: BooksInventory::class, mappedBy: 'librarystock')]
    private Collection $booksInventories;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    public function __construct()
    {
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
            $booksInventory->setLibrarystock($this);
        }

        return $this;
    }

    public function removeBooksInventory(BooksInventory $booksInventory): static
    {
        if ($this->booksInventories->removeElement($booksInventory)) {
            // set the owning side to null (unless already changed)
            if ($booksInventory->getLibrarystock() === $this) {
                $booksInventory->setLibrarystock(null);
            }
        }

        return $this;
    }


    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }
}
