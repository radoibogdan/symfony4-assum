<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200925132802 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC2780F7E20A');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC2780F7E20A FOREIGN KEY (assureur_id) REFERENCES assureur (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC2780F7E20A');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC2780F7E20A FOREIGN KEY (assureur_id) REFERENCES assureur (id)');
    }
}
