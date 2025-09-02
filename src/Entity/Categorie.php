<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Fournisseur;
use App\Entity\Produit;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
#[ORM\Table(name: "categorie")]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'enfants')]
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?Categorie $parent = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    private Collection $enfants;

    #[ORM\ManyToMany(targetEntity: Fournisseur::class, inversedBy: 'categories')]
    #[ORM\JoinTable(name: 'fournisseur_categorie')]
    private Collection $fournisseurs;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Produit::class)]
    private Collection $produits;

    public function __construct()
    {
        $this->enfants = new ArrayCollection();
        $this->fournisseurs = new ArrayCollection();
        $this->produits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;
        return $this;
    }

    public function getParent(): ?Categorie
    {
        return $this->parent;
    }

    public function setParent(?Categorie $parent): static
    {
        $this->parent = $parent;
        return $this;
    }

    /** @return Collection<int, Categorie> */
    public function getEnfants(): Collection
    {
        return $this->enfants;
    }

    public function addEnfant(Categorie $enfant): static
    {
        if (!$this->enfants->contains($enfant)) {
            $this->enfants->add($enfant);
            $enfant->setParent($this);
        }
        return $this;
    }

    public function removeEnfant(Categorie $enfant): static
    {
        if ($this->enfants->removeElement($enfant) && $enfant->getParent() === $this) {
            $enfant->setParent(null);
        }
        return $this;
    }

    /** @return Collection<int, Fournisseur> */
    public function getFournisseurs(): Collection
    {
        return $this->fournisseurs;
    }

    public function addFournisseur(Fournisseur $fournisseur): static
    {
        if (!$this->fournisseurs->contains($fournisseur)) {
            $this->fournisseurs->add($fournisseur);
            $fournisseur->addCategorie($this);
        }
        return $this;
    }

    public function removeFournisseur(Fournisseur $fournisseur): static
    {
        if ($this->fournisseurs->removeElement($fournisseur)) {
            $fournisseur->removeCategorie($this);
        }
        return $this;
    }

    /** @return Collection<int, Produit> */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->setCategorie($this);
        }
        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->produits->removeElement($produit) && $produit->getCategorie() === $this) {
            $produit->setCategorie(null);
        }
        return $this;
    }
}
