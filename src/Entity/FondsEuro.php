<?php

namespace App\Entity;

use App\Repository\FondsEuroRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=FondsEuroRepository::class)
 * @UniqueEntity(fields={"label_fonds"}, message="Ce label est déjà utilisé.")
 * @ORM\HasLifecycleCallbacks()
 * HasLifecycleCallbacks + méthode prePersist =>
 * Permet de modifier l’entité pour enregister la date de création  à la date ou on valide le produit
 */
class FondsEuro
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $label_fonds;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $nom;

    /**
     * @ORM\Column(type="integer")
     */
    private $annee;

    /**
     * @ORM\Column(type="integer")
     */
    private $taux_pb;

    /**
     * @ORM\ManyToMany(targetEntity=Produit::class, mappedBy="fonds_euro")
     */
    private $produits;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creation;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabelFonds(): ?string
    {
        return $this->label_fonds;
    }

    public function setLabelFonds(string $label_fonds): self
    {
        $this->label_fonds = $label_fonds;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getTauxPb(): ?int
    {
        return $this->taux_pb;
    }

    /**
     * Obtenir le taux de participation au bénéfices en décimal: 1500 -> 15.00
     */
    public function getTauxPbFloat(): ?float
    {
        return $this->taux_pb === null ? null : $this->taux_pb/100;
    }

    public function setTauxPb(int $taux_pb): self
    {
        $this->taux_pb = $taux_pb;

        return $this;
    }

    /**
     * @return Collection|Produit[]
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits[] = $produit;
            $produit->addFondsEuro($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->contains($produit)) {
            $this->produits->removeElement($produit);
            $produit->removeFondsEuro($this);
        }

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

    /**
     * Méthode exécutée avant l'insertion en base
     * Modifier l'entité pour enregistrer une date de création de l'utilisateur
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        if ($this->creation === null) {
            $this->creation = new \DateTime();
        }
    }
}
