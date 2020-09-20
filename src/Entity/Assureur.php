<?php

namespace App\Entity;

use App\Repository\AssureurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AssureurRepository::class)
 */
class Assureur
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
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $site;

    /**
     * @ORM\OneToMany(targetEntity=Produit::class, mappedBy="assureur")
     */
    private $produits;

    /**
     * @ORM\OneToMany(targetEntity=AvisAssureur::class, mappedBy="assureur")
     */
    private $avisAssureurs;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
        $this->avisAssureurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSite(): ?string
    {
        return $this->site;
    }

    public function setSite(string $site): self
    {
        $this->site = $site;

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
            $produit->setAssureur($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->contains($produit)) {
            $this->produits->removeElement($produit);
            // set the owning side to null (unless already changed)
            if ($produit->getAssureur() === $this) {
                $produit->setAssureur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|AvisAssureur[]
     */
    public function getAvisAssureurs(): Collection
    {
        return $this->avisAssureurs;
    }

    public function addAvisAssureur(AvisAssureur $avisAssureur): self
    {
        if (!$this->avisAssureurs->contains($avisAssureur)) {
            $this->avisAssureurs[] = $avisAssureur;
            $avisAssureur->setAssureur($this);
        }

        return $this;
    }

    public function removeAvisAssureur(AvisAssureur $avisAssureur): self
    {
        if ($this->avisAssureurs->contains($avisAssureur)) {
            $this->avisAssureurs->removeElement($avisAssureur);
            // set the owning side to null (unless already changed)
            if ($avisAssureur->getAssureur() === $this) {
                $avisAssureur->setAssureur(null);
            }
        }

        return $this;
    }
}
