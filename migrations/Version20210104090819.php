<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210104090819 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD updated_at DATETIME DEFAULT CURRENT_TIMESTAMP, CHANGE creation creation DATETIME DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE produit ADD created_at DATETIME DEFAULT CURRENT_TIMESTAMP, ADD updated_at DATETIME DEFAULT CURRENT_TIMESTAMP');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP updated_at, CHANGE creation creation DATETIME NOT NULL');
        $this->addSql('ALTER TABLE produit DROP created_at, DROP updated_at');
    }
}
