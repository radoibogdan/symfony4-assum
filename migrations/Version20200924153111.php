<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200924153111 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis_assureur DROP FOREIGN KEY FK_9246FFF060BB6FE6');
        $this->addSql('ALTER TABLE avis_assureur ADD CONSTRAINT FK_9246FFF060BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE avis_produit DROP FOREIGN KEY FK_2A67C2160BB6FE6');
        $this->addSql('ALTER TABLE avis_produit ADD CONSTRAINT FK_2A67C2160BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis_assureur DROP FOREIGN KEY FK_9246FFF060BB6FE6');
        $this->addSql('ALTER TABLE avis_assureur ADD CONSTRAINT FK_9246FFF060BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE avis_produit DROP FOREIGN KEY FK_2A67C2160BB6FE6');
        $this->addSql('ALTER TABLE avis_produit ADD CONSTRAINT FK_2A67C2160BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
    }
}
