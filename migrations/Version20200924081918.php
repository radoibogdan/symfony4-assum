<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200924081918 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis_produit DROP FOREIGN KEY FK_2A67C21F347EFB');
        $this->addSql('ALTER TABLE avis_produit ADD CONSTRAINT FK_2A67C21F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis_produit DROP FOREIGN KEY FK_2A67C21F347EFB');
        $this->addSql('ALTER TABLE avis_produit ADD CONSTRAINT FK_2A67C21F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
    }
}
