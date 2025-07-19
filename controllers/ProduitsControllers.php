<?php

require_once 'models/Produits.php';
require_once 'init_smarty.php';

class ProduitsController
{
    private $produitModel;
    private $smarty;

    public function __construct() {
        $this->produitModel = new Produits();
        global $smarty;
        $this->smarty = $smarty;
    }

    // Afficher les produits
    public function liste() {
        $produits =$this->produitModel->lister();
        // On assigne les variables à smarty
        $this->smarty->assign('produits', $produits);
        $this->smarty->display('produits_liste.tpl');
    }

    // Ajouter un produit
    public function add() {
        if (isset($_POST['nom'], $_POST['prix'], $_POST['stock'])) {
            $this->produitModel->ajouter($_POST['nom'], $_POST['prix'], $_POST['stock']);
            // Redirection vers la liste après ajout
            header("Location: index.php?action=produits");
            exit;
        } else {
            // Affiche le formulaire s'il n'a pas encore été soumis
            $this->smarty->display('produits_add.tpl');
        }
    }

    // Supprimer un produit
    public function delete() {
        $this->produitModel->delete($_GET['id_produits']);
        // Rappeler la fonction pour afficher
        $this->liste();
    }

    // Modifier un produit
    public function modifier($id_produits) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifie que les champs sont bien envoyés
            if (isset($_POST['nom'], $_POST['prix'], $_POST['stock'])) {
                $this->produitModel->modifier($_POST['nom'], $_POST['prix'], $_POST['stock'], $id_produits);
                // Redirection après modification
                header("Location: index.php?action=produits");
                exit;
            }
        } else {
            // Affichage du formulaire avec les données existantes
            $produit = Produits::loadById($id_produits);
            if ($produit) {
                // Passage des données à Smarty
                $this->smarty->assign('produit', [
                    'id_produits' => $produit->getId(),
                    'nom' => $produit->getNom(),
                    'prix' => $produit->getPrix(),
                    'stock' => $produit->getStock()
                ]);
                $this->smarty->display('produits_edit.tpl');
            }
        }
    }
}