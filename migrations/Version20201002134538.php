<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201002134538 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE produit_fonds_euro (produit_id INT NOT NULL, fonds_euro_id INT NOT NULL, INDEX IDX_63A5C04F347EFB (produit_id), INDEX IDX_63A5C049F1400DE (fonds_euro_id), PRIMARY KEY(produit_id, fonds_euro_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE produit_fonds_euro ADD CONSTRAINT FK_63A5C04F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_fonds_euro ADD CONSTRAINT FK_63A5C049F1400DE FOREIGN KEY (fonds_euro_id) REFERENCES fonds_euro (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fonds_euro ADD creation DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE produit_fonds_euro');
        $this->addSql('ALTER TABLE fonds_euro DROP creation');
    }
}
