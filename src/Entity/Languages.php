<?php

namespace App\Entity;

use App\Repository\LanguagesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LanguagesRepository::class)]
class Languages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $title = null;

    /**
     * @var Collection<int, BooksInventory>
     */
    #[ORM\OneToMany(targetEntity: BooksInventory::class, mappedBy: 'languages')]
    private Collection $booksInventories;

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
            $booksInventory->setLanguages($this);
        }

        return $this;
    }

    public function removeBooksInventory(BooksInventory $booksInventory): static
    {
        if ($this->booksInventories->removeElement($booksInventory)) {
            // set the owning side to null (unless already changed)
            if ($booksInventory->getLanguages() === $this) {
                $booksInventory->setLanguages(null);
            }
        }

        return $this;
    }
}
