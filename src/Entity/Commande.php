<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCommande = null;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: LigneCommande::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $lignesCommandes;

    #[ORM\ManyToOne(inversedBy: 'commandes', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    public function __construct()
    {
        $this->lignesCommandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTimeInterface $dateCommande): static
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    /**
     * @return Collection<int, LigneCommande>
     */
    public function getLignesCommandes(): Collection
    {
        return $this->lignesCommandes;
    }

    public function addLignesCommande(LigneCommande $lignesCommande): static
    {
        if (!$this->lignesCommandes->contains($lignesCommande)) {
            $this->lignesCommandes->add($lignesCommande);
            $lignesCommande->setCommande($this);
        }

        return $this;
    }

    public function removeLignesCommande(LigneCommande $lignesCommande): static
    {
        if ($this->lignesCommandes->removeElement($lignesCommande)) {
            // set the owning side to null (unless already changed)
            if ($lignesCommande->getCommande() === $this) {
                $lignesCommande->setCommande(null);
            }
        }

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
