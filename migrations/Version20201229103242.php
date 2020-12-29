<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201229103242 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie_uc (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit_categorie_uc (produit_id INT NOT NULL, categorie_uc_id INT NOT NULL, INDEX IDX_2E4D70AEF347EFB (produit_id), INDEX IDX_2E4D70AEB2FB31BD (categorie_uc_id), PRIMARY KEY(produit_id, categorie_uc_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE produit_categorie_uc ADD CONSTRAINT FK_2E4D70AEF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_categorie_uc ADD CONSTRAINT FK_2E4D70AEB2FB31BD FOREIGN KEY (categorie_uc_id) REFERENCES categorie_uc (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit_categorie_uc DROP FOREIGN KEY FK_2E4D70AEB2FB31BD');
        $this->addSql('DROP TABLE categorie_uc');
        $this->addSql('DROP TABLE produit_categorie_uc');
    }
}
