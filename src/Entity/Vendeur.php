<?php

namespace App\Entity;

use App\Repository\VendeurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VendeurRepository::class)]
class Vendeur extends User
{
    #[ORM\OneToMany(mappedBy: 'vendeur', targetEntity: Produit::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $Produits;

    public function __construct()
    {
        $this->Produits = new ArrayCollection();
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->Produits;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->Produits->contains($produit)) {
            $this->Produits->add($produit);
            $produit->setVendeur($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->Produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getVendeur() === $this) {
                $produit->setVendeur(null);
            }
        }

        return $this;
    }

}
