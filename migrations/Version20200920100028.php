<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200920100028 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE assureur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, site VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE avis_assureur (id INT AUTO_INCREMENT NOT NULL, auteur_id INT DEFAULT NULL, assureur_id INT DEFAULT NULL, note INT NOT NULL, avis LONGTEXT NOT NULL, creation DATETIME NOT NULL, INDEX IDX_9246FFF060BB6FE6 (auteur_id), INDEX IDX_9246FFF080F7E20A (assureur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE avis_produit (id INT AUTO_INCREMENT NOT NULL, auteur_id INT DEFAULT NULL, produit_id INT DEFAULT NULL, note INT NOT NULL, commentaire LONGTEXT NOT NULL, creation DATETIME NOT NULL, INDEX IDX_2A67C2160BB6FE6 (auteur_id), INDEX IDX_2A67C21F347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gestion (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, assureur_id INT DEFAULT NULL, categorie_id INT DEFAULT NULL, gestion_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, frais_adhesion INT NOT NULL, frais_versement INT NOT NULL, frais_gestion INT NOT NULL, frais_arbitrage INT NOT NULL, rendement INT DEFAULT NULL, label TINYINT(1) NOT NULL, creation DATE NOT NULL, INDEX IDX_29A5EC2780F7E20A (assureur_id), INDEX IDX_29A5EC27BCF5E72D (categorie_id), INDEX IDX_29A5EC278ADDF3C3 (gestion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, pseudo VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, inscription DATE NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE avis_assureur ADD CONSTRAINT FK_9246FFF060BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE avis_assureur ADD CONSTRAINT FK_9246FFF080F7E20A FOREIGN KEY (assureur_id) REFERENCES assureur (id)');
        $this->addSql('ALTER TABLE avis_produit ADD CONSTRAINT FK_2A67C2160BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE avis_produit ADD CONSTRAINT FK_2A67C21F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC2780F7E20A FOREIGN KEY (assureur_id) REFERENCES assureur (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC278ADDF3C3 FOREIGN KEY (gestion_id) REFERENCES gestion (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis_assureur DROP FOREIGN KEY FK_9246FFF080F7E20A');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC2780F7E20A');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27BCF5E72D');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC278ADDF3C3');
        $this->addSql('ALTER TABLE avis_produit DROP FOREIGN KEY FK_2A67C21F347EFB');
        $this->addSql('ALTER TABLE avis_assureur DROP FOREIGN KEY FK_9246FFF060BB6FE6');
        $this->addSql('ALTER TABLE avis_produit DROP FOREIGN KEY FK_2A67C2160BB6FE6');
        $this->addSql('DROP TABLE assureur');
        $this->addSql('DROP TABLE avis_assureur');
        $this->addSql('DROP TABLE avis_produit');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE gestion');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE user');
    }
}
