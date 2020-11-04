<?php

namespace App\Entity;

use App\Repository\AvisProduitRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AvisProduitRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * HasLifecycleCallbacks + méthode prePersist =>
 * Permet de modifier l’entité pour enregister la date de création à la date ou on valide l'avis
 */
class AvisProduit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="avisProduits")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $auteur;

    /**
     * @ORM\ManyToOne(targetEntity=Produit::class, inversedBy="avisProduits")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $produit;

    /**
     * @ORM\Column(type="integer")
     */
    private $note;

    /**
     * @ORM\Column(type="text")
     */
    private $commentaire;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creation;

    /**
     * @ORM\Column(type="boolean")
     */
    private $approuve = '0';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuteur(): ?User
    {
        return $this->auteur;
    }

    public function setAuteur(?User $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): self
    {
        $this->commentaire = $commentaire;

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

    public function getCreation(): ?\DateTimeInterface
    {
        return $this->creation;
    }

    public function setCreation(\DateTimeInterface $creation): self
    {
        $this->creation = $creation;

        return $this;
    }

    public function getApprouve(): ?bool
    {
        return $this->approuve;
    }

    public function setApprouve(bool $approuve): self
    {
        $this->approuve = $approuve;

        return $this;
    }
}
