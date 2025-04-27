<?php

namespace App\DataFixtures;

use App\Entity\Accounts;
use App\Entity\Authors;
use App\Entity\AuthorsBooks;
use App\Entity\Books;
use App\Entity\BooksInventory;
use App\Entity\Categories;
use App\Entity\Languages;
use App\Entity\LibraryStock;
use App\Entity\Reservations;
use App\Entity\Reviews;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        // create 20 Accounts Users ! Bam!
        $accounts = [];
        for ($i = 1; $i <= 20; $i++) {
            $account = new Accounts();
            $account->setEmail('user'.$i.'@example.com');
            $account->setRoles(['ROLE_USER']);
            $account->setPassword($this->passwordHasher->hashPassword($account, 'password'.$i));
            $account->setUsername('User'.$i);
            $manager->persist($account);
            $accounts [] = $account;
        }

        // create 5 Accounts Librarians ! Bam!
        for ($i = 1; $i <= 5; $i++) {
            $account = new Accounts();
            $account->setEmail('librarian'.$i.'@example.com');
            $account->setUsername('librarian'.$i);
            $account->setRoles(['ROLE_LIBRARIAN']);
            $account->setPassword($this->passwordHasher->hashPassword($account, 'password_lib'.$i));
            $manager->persist($account);
        }

        // create 1 admin
        $admin = new Accounts();
        $admin->setEmail('admin_app@example.com');
        $admin->setUsername('admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin_password'));
        $manager->persist($admin);
        $accounts [] = $admin;

        // create 10 Authors ! Bam!
        $authors = [];
        for ($i = 1; $i <= 10; $i++) {
            $author = new Authors();
            $author->setFirstName('firstName'.$i);
            $author->setLastName('lastName'.$i);
            $author->setBooksCount($i);
            $manager->persist($author);
            $authors [] = $author; 
        }

        // create 5 Categories ! Bam!
        $categories = [];
        for ($i = 1; $i <= 5; $i++) {
            $category = new Categories();
            $category->setTitle('title'.$i);
            $manager->persist($category);
            $categories []= $category;
        }

        // create 40 Books ! Bam!
        $books = [];
        for ($i = 1; $i <= 40; $i++) {
            $day = str_pad(($i % 30) + 1, 2, '0', STR_PAD_LEFT);
            $randomCategory = $categories[array_rand($categories)];
            $book = new Books();
            $book->setTitle('title'.$i);
            $book->setTheme('theme'.$i);
            $book->setPublicationDate(new \DateTime('1998-11-'.$day));
            $book->setCategories($randomCategory);
            $manager->persist($book);
            $books [] = $book;
        }

        // create 5 Languages ! Bam!
        $languages = [];
        for ($i = 1; $i <= 5; $i++) {
            $language = new Languages();
            $language->setTitle('title'.$i);
            $manager->persist($language);
            $languages [] = $language;
        }

        // create 4 Library Stock ! Bam!
        $library = [];
        for ($i = 1; $i <= 4; $i++) {
            $libraryStock = new LibraryStock();
            $libraryStock->setTitle('title'.$i);
            $libraryStock->setAddress($i.' Rue de la Paix, Abidjan (Côte d\'Ivoire)');
            $manager->persist($libraryStock);
            $library [] = $libraryStock; 
        }

        // create 25  Reservations ! Bam!
        for ($i = 1; $i <= 25; $i++) {
            $randomAccount = $accounts[array_rand($accounts)];
            $randomBook = $books[array_rand($books)];
            $reservation = new Reservations();
            $reservation->setCreatedAt(new \DateTimeImmutable());
            $reservation->setAccounts($randomAccount);
            $reservation->setBooks($randomBook);
            $manager->persist($reservation);
        }

        // create 70  Reviews ! Bam!
        for ($i = 1; $i <= 70; $i++) {
            $randomAccount = $accounts[array_rand($accounts)];
            $randomBook = $books[array_rand($books)];
            $review = new Reviews();
            $review->setRating(3);
            $review->setComment('comment'. $i);
            $review->setCreatedAt(new \DateTimeImmutable());
            $review->setAccounts($randomAccount);
            $review->setBooks($randomBook);
            $manager->persist($review);
        }

        // create 40 Authors Books ! Bam!
        for ($i = 1; $i <= 40; $i++) {
            $randomAuthor = $authors[array_rand($authors)];
            $randomBook = $books[array_rand($books)];
            $authorBook = new AuthorsBooks();
            $authorBook->setAuthors($randomAuthor);
            $authorBook->setBooks($randomBook);
            $manager->persist($authorBook);
        }

        // create 20 Books Inventory ! Bam!
        for ($i = 1; $i <= 20; $i++) {
            $randomPrice = mt_rand(500, 10000) / 100;
            $randLibrary = $library[array_rand($library)];
            $randLanguage = $languages[array_rand($languages)];
            $randomBook = $books[array_rand($books)];
            $booksInventory = new BooksInventory();
            $booksInventory->setAvailableCopie($i);
            $booksInventory->setPrice($randomPrice);
            $booksInventory->setLanguages($randLanguage);
            $booksInventory->setBooks($randomBook);
            $booksInventory->setLibrarystock($randLibrary);
            $manager->persist($booksInventory);
        }

        $manager->flush();
    }
}