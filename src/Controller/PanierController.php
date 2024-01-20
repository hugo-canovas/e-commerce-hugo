<?php

namespace App\Controller;

use App\Class\Panier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private RequestStack $requestStack,
    )
    {}

    #[Route('/panier', name: 'app_panier')]
    public function index(Panier $panier): Response
    {

        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
            'panier' => $panier->getFull(),
        ]);
    }

    #[Route('/panier/ajouter/{id}', name: 'app_panier_ajouter')]
    public function addToPanier(Panier $panier, $id): Response
    {
        $panier->add($id);

        return $this->redirectToRoute('app_panier');
    }

    #[Route('/panier/ajout-rapide/{id}', name: 'app_panier_ajout_rapide')]
    public function ajoutRapide(Panier $panier, $id): Response
    {
        $panier->add($id);

        return $this->redirectToRoute('app_produit_index');
    }

    #[Route("/panier/remove", name: "app_panier_remove")]
    public function removePanier(panier $panier): Response
    {
        $panier->remove();

        return $this->redirectToRoute('app_produit_index');
    }

    #[Route("/panier/supprimer/{id}", name: "app_panier_supprimer")]
    public function supprimerDuPanier(Panier $panier, $id): Response
    {
        $panier->delete($id);

        return $this->redirectToRoute('app_panier');
    }

    #[Route("/panier/decrease/{id}", name: "app_panier_decrease")]
    public function decrease(Panier $panier, $id): Response
    {
        $panier->decrease($id);

        return $this->redirectToRoute('app_panier');
    }
}
