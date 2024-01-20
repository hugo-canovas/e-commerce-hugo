<?php

namespace App\Class;

use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Panier
{
    private $requestStack;
    private $entityManager;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        $this->requestStack = $requestStack;
        $this->entityManager = $entityManager;
    }

    public function add($id)
    {
        $panier = $this->getPanier();

        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }

        $this->setPanier($panier);
    }

    public function get()
    {
        return $this->getPanier();
    }

    public function delete($id)
    {
        $panier = $this->getPanier();
        unset($panier[$id]);
        $this->setPanier($panier);
    }

    public function getFull()
    {
        $panierComplete = [];

        if ($this->get()) {
            foreach ($this->get() as $id => $quantite) {
                $produit = $this->entityManager->getRepository(Produit::class)->find($id);

                if (!$produit) {
                    $this->delete($id);
                    continue;
                }

                $panierComplete[] = [
                    'produit' => $produit,
                    'quantite' => $quantite
                ];
            }
        }

        return $panierComplete;
    }

    private function getPanier()
    {
        $session = $this->requestStack->getCurrentRequest()->getSession();
        return $session->get('panier', []);
    }

    private function setPanier(array $panier)
    {
        $session = $this->requestStack->getCurrentRequest()->getSession();
        $session->set('panier', $panier);
    }

    public function remove()
    {
        $session = $this->requestStack->getCurrentRequest()->getSession();
        return $session->remove('panier');
    }

    public function decrease($id)
    {
        $panier = $this->getPanier();
    
        if ($panier[$id] > 1) {
            $panier[$id]--;
        } else {
            unset($panier[$id]);
        }
    
        $this->setPanier($panier);
    }
}
