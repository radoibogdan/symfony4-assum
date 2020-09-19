<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 */
class Produit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $frais_adhesion;

    /**
     * @ORM\Column(type="integer")
     */
    private $frais_versement;

    /**
     * @ORM\Column(type="integer")
     */
    private $frais_gestion;

    /**
     * @ORM\Column(type="integer")
     */
    private $frais_arbitrage;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rendement;

    /**
     * @ORM\Column(type="boolean")
     */
    private $label;

    /**
     * @ORM\Column(type="date")
     */
    private $creation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getFraisAdhesion(): ?int
    {
        return $this->frais_adhesion;
    }

    public function setFraisAdhesion(int $frais_adhesion): self
    {
        $this->frais_adhesion = $frais_adhesion;

        return $this;
    }

    public function getFraisVersement(): ?int
    {
        return $this->frais_versement;
    }

    public function setFraisVersement(int $frais_versement): self
    {
        $this->frais_versement = $frais_versement;

        return $this;
    }

    public function getFraisGestion(): ?int
    {
        return $this->frais_gestion;
    }

    public function setFraisGestion(int $frais_gestion): self
    {
        $this->frais_gestion = $frais_gestion;

        return $this;
    }

    public function getFraisArbitrage(): ?int
    {
        return $this->frais_arbitrage;
    }

    public function setFraisArbitrage(int $frais_arbitrage): self
    {
        $this->frais_arbitrage = $frais_arbitrage;

        return $this;
    }

    public function getRendement(): ?int
    {
        return $this->rendement;
    }

    public function setRendement(?int $rendement): self
    {
        $this->rendement = $rendement;

        return $this;
    }

    public function getLabel(): ?bool
    {
        return $this->label;
    }

    public function setLabel(bool $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getCreation(): ?\DateTimeInterface
    {
        return $this->creation;
    }

    public function setCreation(\DateTimeInterface $creation): self
    {
        $this->creation = $creation;

        return $this;
    }
}
