<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @ORM\ManyToOne(targetEntity=Assureur::class, inversedBy="produits")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $assureur;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="produits")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $categorie;

    /**
     * @ORM\OneToMany(targetEntity=AvisProduit::class, mappedBy="produit")
     */
    private $avisProduits;

    /**
     * @ORM\ManyToMany(targetEntity=Gestion::class, inversedBy="produits")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $gestion;

    public function __construct()
    {
        $this->avisProduits = new ArrayCollection();
        $this->gestion = new ArrayCollection();
    }

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

    /**
     * Obtenir les frais_adhesion en décimal: 1500 -> 15.00
     */
    public function getFraisAdhesionFloat(): ?float
    {
        return $this->frais_adhesion === null ? null : $this->frais_adhesion/100;
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

    /**
     * Obtenir les frais_versement en décimal: 1500 -> 15.00
     */
    public function getFraisVersementFloat(): ?float
    {
        return $this->frais_versement === null ? null : $this->frais_versement/100;
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

    /**
     * Obtenir les frais_gestion en décimal: 1500 -> 15.00
     */
    public function getFraisGestionFloat(): ?float
    {
        return $this->frais_gestion === null ? null : $this->frais_gestion/100;
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

    /**
     * Obtenir les frais_arbitrage en décimal: 1500 -> 15.00
     */
    public function getFraisArbitrageFloat(): ?float
    {
        return $this->frais_arbitrage === null ? null : $this->frais_arbitrage/100;
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

    /**
     * Obtenir le rendement en décimal: 1500 -> 15.00
     */
    public function getRendementFloat(): ?float
    {
        return $this->rendement === null ? null : $this->rendement/100;
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

    public function getLabelValue()
    {
        return $this->label == 1 ? 'Oui' : 'Non';
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

    public function getAssureur(): ?Assureur
    {
        return $this->assureur;
    }

    public function setAssureur(?Assureur $assureur): self
    {
        $this->assureur = $assureur;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection|AvisProduit[]
     */
    public function getAvisProduits(): Collection
    {
        return $this->avisProduits;
    }

    public function addAvisProduit(AvisProduit $avisProduit): self
    {
        if (!$this->avisProduits->contains($avisProduit)) {
            $this->avisProduits[] = $avisProduit;
            $avisProduit->setProduit($this);
        }

        return $this;
    }

    public function removeAvisProduit(AvisProduit $avisProduit): self
    {
        if ($this->avisProduits->contains($avisProduit)) {
            $this->avisProduits->removeElement($avisProduit);
            // set the owning side to null (unless already changed)
            if ($avisProduit->getProduit() === $this) {
                $avisProduit->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Gestion[]
     */
    public function getGestion(): Collection
    {
        return $this->gestion;
    }

    public function addGestion(Gestion $gestion): self
    {
        if (!$this->gestion->contains($gestion)) {
            $this->gestion[] = $gestion;
        }

        return $this;
    }

    public function removeGestion(Gestion $gestion): self
    {
        if ($this->gestion->contains($gestion)) {
            $this->gestion->removeElement($gestion);
        }

        return $this;
    }
}
