<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Client;
use App\Entity\Utilisateur;

#[ORM\Entity]
#[ORM\Table(name: "Commercial")]
class Commercial
{
    #[ORM\Id]
    #[ORM\Column(type: "string", length: 255)]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    private string $nom;

    #[ORM\Column(length: 255)]
    private string $email;

    #[ORM\OneToMany(mappedBy: "commercial", targetEntity: Client::class)]
    private Collection $clients;

    #[ORM\OneToMany(mappedBy: "commercial", targetEntity: Utilisateur::class)]
    private Collection $utilisateurs;

    public function __construct()
    {
        $this->clients = new ArrayCollection();
        $this->utilisateurs = new ArrayCollection();
    }

    public function getId(): ?string { return $this->id; }
    public function setId(string $id): static { $this->id = $id; return $this; }

    public function getNom(): string { return $this->nom; }
    public function setNom(string $nom): static { $this->nom = $nom; return $this; }

    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): static { $this->email = $email; return $this; }

    public function getClients(): Collection { return $this->clients; }
    public function addClient(Client $client): static { 
        if (!$this->clients->contains($client)) {
            $this->clients->add($client);
            $client->setCommercial($this);
        }
        return $this;
    }
    public function removeClient(Client $client): static {
        if ($this->clients->removeElement($client)) $client->setCommercial(null);
        return $this;
    }

    public function getUtilisateurs(): Collection { return $this->utilisateurs; }
    public function addUtilisateur(Utilisateur $user): static {
        if (!$this->utilisateurs->contains($user)) {
            $this->utilisateurs->add($user);
            $user->setCommercial($this);
        }
        return $this;
    }
    public function removeUtilisateur(Utilisateur $user): static {
        if ($this->utilisateurs->removeElement($user)) $user->setCommercial(null);
        return $this;
    }
}
