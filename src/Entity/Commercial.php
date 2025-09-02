<?php

namespace App\Entity;

use App\Repository\CommercialRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Client;
use App\Entity\Utilisateur;

#[ORM\Entity(repositoryClass: CommercialRepository::class)]
#[ORM\Table(name: "commercial")]
class Commercial
{

    #[ORM\Id]
    #[ORM\Column(type: "string", length: 255)]
    private ?string $id = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    private ?string $email = null;

    /**
     * One commercial -> many clients
     */
    #[ORM\OneToMany(mappedBy: "commercial", targetEntity: Client::class)]
    private Collection $clients;

    /**
     * One commercial -> many utilisateurs (users)
     */
    #[ORM\OneToMany(mappedBy: "commercial", targetEntity: Utilisateur::class)]
    private Collection $utilisateurs;

    public function __construct()
    {
        $this->clients = new ArrayCollection();
        $this->utilisateurs = new ArrayCollection();
    }

    // If you prefer auto-generated string ids you can add a setter or factory.
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Set the id (useful if you want to seed a specific string key)
     */
    public function setId(string $id): static
    {
        $this->id = $id;
        return $this;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return Collection<int, Client>
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): static
    {
        if (!$this->clients->contains($client)) {
            $this->clients->add($client);
            // set inverse side — assumes Client has setCommercial(?Commercial)
            $client->setCommercial($this);
        }

        return $this;
    }

    public function removeClient(Client $client): static
    {
        if ($this->clients->removeElement($client)) {
            if (method_exists($client, 'getCommercial') && $client->getCommercial() === $this) {
                $client->setCommercial(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getUtilisateurs(): Collection
    {
        return $this->utilisateurs;
    }

    public function addUtilisateur(Utilisateur $utilisateur): static
    {
        if (!$this->utilisateurs->contains($utilisateur)) {
            $this->utilisateurs->add($utilisateur);
            // set inverse side — assumes Utilisateur has setCommercial(?Commercial)
            $utilisateur->setCommercial($this);
        }

        return $this;
    }

    public function removeUtilisateur(Utilisateur $utilisateur): static
    {
        if ($this->utilisateurs->removeElement($utilisateur)) {
            if (method_exists($utilisateur, 'getCommercial') && $utilisateur->getCommercial() === $this) {
                $utilisateur->setCommercial(null);
            }
        }

        return $this;
    }
}
