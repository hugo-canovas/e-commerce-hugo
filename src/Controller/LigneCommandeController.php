<?php

namespace App\Controller;

use App\Class\Panier;
use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\LigneCommande;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LigneCommandeController extends AbstractController
{
    // LigneCommandeController.php
#[Route('/ligne/commande', name: 'app_ligne_commande')]
public function new(
    EntityManagerInterface $entityManager,
    Panier $panier
): Response {
    $produits = $panier->getFull();
    $commande = new Commande();
    $user = $this->getUser();

    if ($user instanceof User) {
        foreach ($produits as $produit) {

            $ligneCommande = new LigneCommande();
            $ligneCommande->setQteCommande($produit['quantite']);
            $ligneCommande->setCommande($commande);
            $ligneCommande->setProduit($produit['produit']);
            $commande->setDateCommande(new \DateTime());
            $commande->setClient($user);
            
            $entityManager->persist($ligneCommande);
            $entityManager->flush();
            
        }
        $panier->remove();
        $entityManager->persist($commande);
        return $this->redirectToRoute('app_home');
    }

    return $this->render('ligne_commande/index.html.twig', [
        'controller_name' => 'LigneCommandeController',
    ]);
}

}
