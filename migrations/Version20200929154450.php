<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200929154450 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE produit_gestion (produit_id INT NOT NULL, gestion_id INT NOT NULL, INDEX IDX_18F9DEC9F347EFB (produit_id), INDEX IDX_18F9DEC98ADDF3C3 (gestion_id), PRIMARY KEY(produit_id, gestion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE produit_gestion ADD CONSTRAINT FK_18F9DEC9F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_gestion ADD CONSTRAINT FK_18F9DEC98ADDF3C3 FOREIGN KEY (gestion_id) REFERENCES gestion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC278ADDF3C3');
        $this->addSql('DROP INDEX IDX_29A5EC278ADDF3C3 ON produit');
        $this->addSql('ALTER TABLE produit DROP gestion_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE produit_gestion');
        $this->addSql('ALTER TABLE produit ADD gestion_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC278ADDF3C3 FOREIGN KEY (gestion_id) REFERENCES gestion (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_29A5EC278ADDF3C3 ON produit (gestion_id)');
    }
}
