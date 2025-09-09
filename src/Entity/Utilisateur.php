<?php
// src/Entity/Utilisateur.php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Commercial;
use App\Entity\Client;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\Table(name: "utilisateur")]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(type: "text")]
    private ?string $password = null;

    #[ORM\Column(type: "string", length: 50)]
    private string $role = 'USER'; // Default value

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $dateCreation;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $actif = null;

    #[ORM\ManyToOne(targetEntity: Commercial::class, inversedBy: 'utilisateurs')]
    #[ORM\JoinColumn(name: "Id_Commercial", referencedColumnName: "id", nullable: true, onDelete: "SET NULL")]
    private ?Commercial $commercial = null;

    #[ORM\ManyToOne(targetEntity: Client::class)]
    #[ORM\JoinColumn(name: "Id_Client", referencedColumnName: "id", nullable: true, onDelete: "SET NULL")]
    private ?Client $client = null;

    #[ORM\Column(type: "string", length: 50, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(type: "string", length: 50, nullable: true)]
    private ?string $prenom = null;

    public function __construct()
    {
        $this->dateCreation = new \DateTimeImmutable();
    }

    // --- getters / setters ---

    public function getId(): ?int { return $this->id; }
    public function getEmail(): ?string { return $this->email; }
    public function setEmail(string $email): static { $this->email = $email; return $this; }

    public function getPassword(): ?string { return $this->password; }
    public function setPassword(string $password): static { $this->password = $password; return $this; }

    public function getRole(): string { return $this->role; }
    public function setRole(string $role): static { $this->role = $role; return $this; }

    public function getDateCreation(): \DateTimeInterface { return $this->dateCreation; }
    public function setDateCreation(\DateTimeInterface $dateCreation): static { $this->dateCreation = $dateCreation; return $this; }

    public function isActif(): ?bool { return $this->actif; }
    public function setActif(?bool $actif): static { $this->actif = $actif; return $this; }

    public function getCommercial(): ?Commercial { return $this->commercial; }
    public function setCommercial(?Commercial $commercial): static { $this->commercial = $commercial; return $this; }

    public function getClient(): ?Client { return $this->client; }
    public function setClient(?Client $client): static { $this->client = $client; return $this; }

    public function getNom(): ?string { return $this->nom; }
    public function setNom(?string $nom): static { $this->nom = $nom; return $this; }

    public function getPrenom(): ?string { return $this->prenom; }
    public function setPrenom(?string $prenom): static { $this->prenom = $prenom; return $this; }

    // --- UserInterface / PasswordAuthenticatedUserInterface ---
    public function getUserIdentifier(): string { return (string) $this->email; }

    public function getRoles(): array
    {
        $roles = [strtoupper('ROLE_' . $this->role), 'ROLE_USER'];
        return array_unique($roles);
    }

    public function eraseCredentials(): void {}
}
