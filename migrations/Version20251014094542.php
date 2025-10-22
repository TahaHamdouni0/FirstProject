<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251014094542 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE author ADD nb_books INT DEFAULT NULL');
        $this->addSql('ALTER TABLE book ADD publication_date DATETIME NOT NULL, CHANGE author_id author_id INT DEFAULT NULL, CHANGE title title VARCHAR(255) NOT NULL, CHANGE category category VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE author DROP nb_books');
        $this->addSql('ALTER TABLE book DROP publication_date, CHANGE author_id author_id INT NOT NULL, CHANGE title title VARCHAR(150) NOT NULL, CHANGE category category VARCHAR(100) NOT NULL');
    }
}
