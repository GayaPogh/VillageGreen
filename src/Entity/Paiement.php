<?php
// src/Entity/Paiement.php

namespace App\Entity;

use App\Repository\PaiementRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Commande;

#[ORM\Entity(repositoryClass: PaiementRepository::class)]
#[ORM\Table(name: "paiement")]
class Paiement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 20)]
    private ?string $type = null; // remplacer ENUM par string

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $datePaiement = null;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2, nullable: true)]
    private ?string $montant = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $valide = null;

    #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: 'paiements')]
    #[ORM\JoinColumn(name: "Id_Commande", referencedColumnName: "id", nullable: true, onDelete: "CASCADE")]
    private ?Commande $commande = null;

    // --- getters / setters ---
    public function getId(): ?int { return $this->id; }

    public function getType(): ?string { return $this->type; }
    public function setType(string $type): static { $this->type = $type; return $this; }

    public function getDatePaiement(): ?\DateTimeInterface { return $this->datePaiement; }
    public function setDatePaiement(?\DateTimeInterface $datePaiement): static { $this->datePaiement = $datePaiement; return $this; }

    public function getMontant(): ?string { return $this->montant; }
    public function setMontant(?string $montant): static { $this->montant = $montant; return $this; }

    public function isValide(): ?bool { return $this->valide; }
    public function setValide(?bool $valide): static { $this->valide = $valide; return $this; }

    public function getCommande(): ?Commande { return $this->commande; }
    public function setCommande(?Commande $commande): static { $this->commande = $commande; return $this; }
}
