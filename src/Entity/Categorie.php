<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Produit;
use App\Entity\Fournisseur;

#[ORM\Entity]
#[ORM\Table(name: "Categorie")]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $nom;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: "enfants")]
    #[ORM\JoinColumn(name: "Id_Categorie_Parent", referencedColumnName: "id", nullable: true, onDelete: "SET NULL")]
    private ?Categorie $parent = null;

    #[ORM\OneToMany(mappedBy: "parent", targetEntity: self::class)]
    private Collection $enfants;

    #[ORM\ManyToMany(targetEntity: Fournisseur::class, inversedBy: "categories")]
    #[ORM\JoinTable(name: "FournisseurCategorie")]
    private Collection $fournisseurs;

    #[ORM\OneToMany(mappedBy: "categorie", targetEntity: Produit::class)]
    private Collection $produits;

    public function __construct() {
        $this->enfants = new ArrayCollection();
        $this->fournisseurs = new ArrayCollection();
        $this->produits = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function setNom(string $n): static { $this->nom = $n; return $this; }
    public function getImage(): ?string { return $this->image; }
    public function setImage(?string $img): static { $this->image = $img; return $this; }
    public function getParent(): ?Categorie { return $this->parent; }
    public function setParent(?Categorie $p): static { $this->parent = $p; return $this; }

    public function getEnfants(): Collection { return $this->enfants; }
    public function addEnfant(Categorie $c): static { if(!$this->enfants->contains($c)) $this->enfants->add($c); return $this; }

    public function getFournisseurs(): Collection { return $this->fournisseurs; }
    public function addFournisseur(Fournisseur $f): static { if(!$this->fournisseurs->contains($f)) $this->fournisseurs->add($f); return $this; }

       public function getProduits(): Collection { return $this->produits; }
    public function addProduit(Produit $p): static { 
        if (!$this->produits->contains($p)) $this->produits->add($p); 
        return $this; 
    }

    public function removeFournisseur(Fournisseur $fournisseur): static
{
    if ($this->fournisseurs->removeElement($fournisseur)) {
        $fournisseur->getCategories()->removeElement($this);
    }
    return $this;
}

}
