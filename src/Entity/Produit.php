<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * HasLifecycleCallbacks et la méthode prePersist retrouvée plus bas
 * permet de modifier l’entité pour enregister la date de création à la date ou on crée l'article dans le BO
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
     * @ORM\Column(type="string", length=255)
     */
    private $frais_adhesion;

    /**
     * @ORM\Column(type="integer")
     */
    private $frais_versement;

    /**
     * @ORM\Column(type="integer")
     */
    private $frais_gestion_euro;

    /**
     * @ORM\Column(type="integer")
     */
    private $frais_gestion_uc;

    /**
     * @ORM\Column(type="integer")
     */
    private $frais_arbitrage;

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

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $imageFilename;

    /**
     * @ORM\ManyToMany(targetEntity=FondsEuro::class, inversedBy="produits")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $fonds_euro;

    /**
     * @ORM\Column(type="integer")
     */
    private $versement_initial;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nb_uc_disponibles;

    /**
     * @ORM\ManyToMany(targetEntity=CategorieUC::class, inversedBy="produits")
     */
    private $categories_uc;

    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $updatedAt;

    public function __construct()
    {
        $this->avisProduits = new ArrayCollection();
        $this->gestion = new ArrayCollection();
        $this->fonds_euro = new ArrayCollection();
        $this->categories_uc = new ArrayCollection();
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

    public function getFraisAdhesion(): ?string
    {
        return $this->frais_adhesion;
    }

    public function setFraisAdhesion(string $frais_adhesion): self
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

    public function getFraisGestionEuro(): ?int
    {
        return $this->frais_gestion_euro;
    }

    /**
     * Obtenir les frais_gestion_euro en décimal: 1500 -> 15.00
     */
    public function getFraisGestionEuroFloat(): ?float
    {
        return $this->frais_gestion_euro === null ? null : $this->frais_gestion_euro/100;
    }

    public function setFraisGestionEuro(int $frais_gestion_euro): self
    {
        $this->frais_gestion_euro = $frais_gestion_euro;

        return $this;
    }

    public function getFraisGestionUc(): ?int
    {
        return $this->frais_gestion_uc;
    }

    /**
     * Obtenir les frais_gestion_uc en décimal: 1500 -> 15.00
     */
    public function getFraisGestionUcFloat(): ?float
    {
        return $this->frais_gestion_uc === null ? null : $this->frais_gestion_uc/100;
    }

    public function setFraisGestionUc(int $frais_gestion_uc): self
    {
        $this->frais_gestion_uc = $frais_gestion_uc;

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

    public function setCreation(?\DateTimeInterface $creation): self
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
     * N'est pas reliée à la base de données, juste renvoie la moyenne des avis de tous les temps
     * @return null|integer
     */
    public function getMoyenneProduit()
    {
        if (!$this->avisProduits[0]){
            return null;
        }

        $cumul_notes = 0;
        $nombre_notes = 0;
        /** @var AvisProduit $avis_individuel */
        foreach ($this->avisProduits as $avis_individuel) {
            // si l'avis est approuvé, dans ce cas uniquement, on prends en compte la note
           if ($avis_individuel->getApprouve()) {
               $cumul_notes += $avis_individuel->getNote();
               $nombre_notes++;
           }
        }

        return $nombre_notes === 0 ? null : $cumul_notes/$nombre_notes ;
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

    public function getImageFilename()
    {
        return $this->imageFilename;
    }

    public function setImageFilename($imageFilename)
    {
        $this->imageFilename = $imageFilename;

        return $this;
    }

    /**
     * @return Collection|FondsEuro[]
     */
    public function getFondsEuro(): Collection
    {
        return $this->fonds_euro;
    }

    public function addFondsEuro(FondsEuro $fondsEuro): self
    {
        if (!$this->fonds_euro->contains($fondsEuro)) {
            $this->fonds_euro[] = $fondsEuro;
        }

        return $this;
    }

    public function removeFondsEuro(FondsEuro $fondsEuro): self
    {
        if ($this->fonds_euro->contains($fondsEuro)) {
            $this->fonds_euro->removeElement($fondsEuro);
        }

        return $this;
    }

    public function getVersementInitial(): ?int
    {
        return $this->versement_initial;
    }

    public function setVersementInitial(?int $versement_initial): self
    {
        $this->versement_initial = $versement_initial;

        return $this;
    }

    public function getNbUcDisponibles(): ?string
    {
        return $this->nb_uc_disponibles;
    }

    public function setNbUcDisponibles(?string $nb_uc_disponibles): self
    {
        $this->nb_uc_disponibles = $nb_uc_disponibles;

        return $this;
    }

    /**
     * @return Collection|CategorieUC[]
     */
    public function getCategoriesUc(): Collection
    {
        return $this->categories_uc;
    }

    public function addCategoriesUc(CategorieUC $categoriesUc): self
    {
        if (!$this->categories_uc->contains($categoriesUc)) {
            $this->categories_uc[] = $categoriesUc;
        }

        return $this;
    }

    public function removeCategoriesUc(CategorieUC $categoriesUc): self
    {
        if ($this->categories_uc->contains($categoriesUc)) {
            $this->categories_uc->removeElement($categoriesUc);
        }

        return $this;
    }

    /**
     * Méthode exécutée avant l'insertion en base / après la création du produit dans le BO
     * Modifie l'entité pour enregistrer une date de création du produit
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        if ($this->createdAt === null) {
            $this->createdAt = new \DateTime();
        }
        if ($this->updatedAt === null) {
            $this->updatedAt = new \DateTime();
        }
    }

    /**
     * Méthode exécutée avant la modification en base / après la création du produit dans le BO
     * Modifie l'entité pour enregistrer une date de modification du produit
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        if ($this->updatedAt === null) {
            $this->updatedAt = new \DateTime();
        } else {
            $this->updatedAt = new \DateTime();
        }
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
