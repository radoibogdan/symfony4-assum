<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * HasLifecycleCallbacks + méthode prePersist =>
 * Permet de modifier l’entité pour enregister la date de création  à la date ou on valide le produit
 * @UniqueEntity(fields={"email"}, message="Cet e-mail est déjà utilisé par quelqu'un.")
 * @UniqueEntity(fields={"pseudo"}, message="Ce pseudo est déjà utilisé par quelqu'un.")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\Regex(
     *     pattern="/^(?=.*[a-zà-ÿ])(?=.*[A-ZÀ-Ÿ])(?=.*[0-9])(?=.*[^a-zà-ÿA-ZÀ-Ÿ0-9]).{12,}$/",
     *     message="Le mot de passe doit être composé de 12 caractères dont un minimum : 1 lettre majuscule, 1 lettre minuscule, 1 chiffre et 1 caractère spécial (dans un ordre aléatoire)."
     * )
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telephone;

    /**
     * @ORM\Column(type="date")
     */
    private $inscription;

    /**
     * @ORM\OneToMany(targetEntity=AvisProduit::class, mappedBy="auteur")
     */
    private $avisProduits;

    /**
     * @ORM\OneToMany(targetEntity=AvisAssureur::class, mappedBy="auteur")
     */
    private $avisAssureurs;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="auteur")
     */
    private $articles;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $activation_token;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $reset_token;

      public function __construct()
    {
        $this->avisProduits = new ArrayCollection();
        $this->avisAssureurs = new ArrayCollection();
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

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

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getInscription(): ?\DateTimeInterface
    {
        return $this->inscription;
    }

    public function setInscription(\DateTimeInterface $inscription): self
    {
        $this->inscription = $inscription;

        return $this;
    }

    /**
     * Méthode exécutée avant l'insertion en base
     * Modifier l'entité pour enregistrer une date de création de l'utilisateur
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        if ($this->inscription === null) {
            $this->inscription = new \DateTime();
        }
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
            $avisProduit->setAuteur($this);
        }

        return $this;
    }

    public function removeAvisProduit(AvisProduit $avisProduit): self
    {
        if ($this->avisProduits->contains($avisProduit)) {
            $this->avisProduits->removeElement($avisProduit);
            // set the owning side to null (unless already changed)
            if ($avisProduit->getAuteur() === $this) {
                $avisProduit->setAuteur(null);
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
            $avisAssureur->setAuteur($this);
        }

        return $this;
    }

    public function removeAvisAssureur(AvisAssureur $avisAssureur): self
    {
        if ($this->avisAssureurs->contains($avisAssureur)) {
            $this->avisAssureurs->removeElement($avisAssureur);
            // set the owning side to null (unless already changed)
            if ($avisAssureur->getAuteur() === $this) {
                $avisAssureur->setAuteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setAuteur($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getAuteur() === $this) {
                $article->setAuteur(null);
            }
        }

        return $this;
    }

    /**
     * If user clicks on the link he receives after he registers, his activation token is deleted, thus making him VERIFIED
     * @return bool
     */
    public function isVerified(): bool
    {
        if ($this->activation_token === null) {
            return true;
        }
        return false;
    }

    public function getActivationToken(): ?string
    {
        return $this->activation_token;
    }

    public function setActivationToken(?string $activation_token): self
    {
        $this->activation_token = $activation_token;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->reset_token;
    }

    public function setResetToken(?string $reset_token): self
    {
        $this->reset_token = $reset_token;

        return $this;
    }
}
