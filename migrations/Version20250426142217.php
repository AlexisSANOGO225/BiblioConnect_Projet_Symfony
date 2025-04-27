<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250426142217 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE accounts (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE authors (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, books_count INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE authors_books (id INT AUTO_INCREMENT NOT NULL, authors_id INT DEFAULT NULL, books_id INT DEFAULT NULL, INDEX IDX_2DFDA3CB6DE2013A (authors_id), INDEX IDX_2DFDA3CB7DD8AC20 (books_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE books (id INT AUTO_INCREMENT NOT NULL, categories_id INT DEFAULT NULL, title VARCHAR(100) NOT NULL, theme VARCHAR(255) NOT NULL, publication_date DATE DEFAULT NULL, INDEX IDX_4A1B2A92A21214B7 (categories_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE books_inventory (id INT AUTO_INCREMENT NOT NULL, librarystock_id INT DEFAULT NULL, languages_id INT DEFAULT NULL, books_id INT DEFAULT NULL, available_copie INT NOT NULL, price NUMERIC(7, 2) NOT NULL, INDEX IDX_EF9ABC88344385D (librarystock_id), INDEX IDX_EF9ABC885D237A9A (languages_id), INDEX IDX_EF9ABC887DD8AC20 (books_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE languages (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE library_stock (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE reservations (id INT AUTO_INCREMENT NOT NULL, accounts_id INT DEFAULT NULL, books_id INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_4DA239CC5E8CE8 (accounts_id), INDEX IDX_4DA2397DD8AC20 (books_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE reviews (id INT AUTO_INCREMENT NOT NULL, accounts_id INT DEFAULT NULL, books_id INT DEFAULT NULL, rating INT DEFAULT NULL, comment LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_6970EB0FCC5E8CE8 (accounts_id), INDEX IDX_6970EB0F7DD8AC20 (books_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE authors_books ADD CONSTRAINT FK_2DFDA3CB6DE2013A FOREIGN KEY (authors_id) REFERENCES authors (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE authors_books ADD CONSTRAINT FK_2DFDA3CB7DD8AC20 FOREIGN KEY (books_id) REFERENCES books (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE books ADD CONSTRAINT FK_4A1B2A92A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE books_inventory ADD CONSTRAINT FK_EF9ABC88344385D FOREIGN KEY (librarystock_id) REFERENCES library_stock (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE books_inventory ADD CONSTRAINT FK_EF9ABC885D237A9A FOREIGN KEY (languages_id) REFERENCES languages (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE books_inventory ADD CONSTRAINT FK_EF9ABC887DD8AC20 FOREIGN KEY (books_id) REFERENCES books (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservations ADD CONSTRAINT FK_4DA239CC5E8CE8 FOREIGN KEY (accounts_id) REFERENCES accounts (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservations ADD CONSTRAINT FK_4DA2397DD8AC20 FOREIGN KEY (books_id) REFERENCES books (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reviews ADD CONSTRAINT FK_6970EB0FCC5E8CE8 FOREIGN KEY (accounts_id) REFERENCES accounts (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reviews ADD CONSTRAINT FK_6970EB0F7DD8AC20 FOREIGN KEY (books_id) REFERENCES books (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE authors_books DROP FOREIGN KEY FK_2DFDA3CB6DE2013A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE authors_books DROP FOREIGN KEY FK_2DFDA3CB7DD8AC20
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE books DROP FOREIGN KEY FK_4A1B2A92A21214B7
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE books_inventory DROP FOREIGN KEY FK_EF9ABC88344385D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE books_inventory DROP FOREIGN KEY FK_EF9ABC885D237A9A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE books_inventory DROP FOREIGN KEY FK_EF9ABC887DD8AC20
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservations DROP FOREIGN KEY FK_4DA239CC5E8CE8
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservations DROP FOREIGN KEY FK_4DA2397DD8AC20
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reviews DROP FOREIGN KEY FK_6970EB0FCC5E8CE8
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reviews DROP FOREIGN KEY FK_6970EB0F7DD8AC20
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE accounts
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE authors
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE authors_books
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE books
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE books_inventory
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE categories
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE languages
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE library_stock
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE reservations
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE reviews
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
