<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210827141857 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE news ADD author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE news ADD creation_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE news ADD start_publication_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE news ADD end_publication_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE news ADD publication_status VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE news ADD update_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT FK_1DD39950F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_1DD39950F675F31B ON news (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE news DROP CONSTRAINT FK_1DD39950F675F31B');
        $this->addSql('DROP INDEX IDX_1DD39950F675F31B');
        $this->addSql('ALTER TABLE news DROP author_id');
        $this->addSql('ALTER TABLE news DROP creation_date');
        $this->addSql('ALTER TABLE news DROP start_publication_date');
        $this->addSql('ALTER TABLE news DROP end_publication_date');
        $this->addSql('ALTER TABLE news DROP publication_status');
        $this->addSql('ALTER TABLE news DROP update_date');
    }
}
