<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Commercial;
use App\Entity\Client;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\Table(name: "utilisateur")]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(type: "text")]
    private ?string $motDePasseH = null;

    #[ORM\Column(type: "string", length: 50)]
    private ?string $role = null; // enum: 'admin', 'commercial', 'client'

    #[ORM\Column(type: "datetime", options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $actif = null;

    #[ORM\ManyToOne(targetEntity: Commercial::class, inversedBy: 'utilisateurs')]
    #[ORM\JoinColumn(name: "Id_Commercial", referencedColumnName: "id", nullable: true, onDelete: "SET NULL")]
    private ?Commercial $commercial = null;

    #[ORM\ManyToOne(targetEntity: Client::class)]
    #[ORM\JoinColumn(name: "Id_Client", referencedColumnName: "id", nullable: true, onDelete: "SET NULL")]
    private ?Client $client = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getMotDePasseH(): ?string
    {
        return $this->motDePasseH;
    }

    public function setMotDePasseH(string $motDePasseH): static
    {
        $this->motDePasseH = $motDePasseH;
        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;
        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): static
    {
        $this->dateCreation = $dateCreation;
        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(?bool $actif): static
    {
        $this->actif = $actif;
        return $this;
    }

    public function getCommercial(): ?Commercial
    {
        return $this->commercial;
    }

    public function setCommercial(?Commercial $commercial): static
    {
        $this->commercial = $commercial;
        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;
        return $this;
    }
}
