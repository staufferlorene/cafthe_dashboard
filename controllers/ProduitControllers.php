<?php

require_once 'models/Produit.php';
require_once 'init_smarty.php';

class ProduitController
{
    private $produitModel;
    private $smarty;

    public function __construct() {
        $this->produitModel = new Produit();
        global $smarty;
        $this->smarty = $smarty;
    }

    // Afficher les produits
    public function liste() {
        $produit =$this->produitModel->lister();
        // On assigne les variables à smarty
        $this->smarty->assign('produit', $produit);
        $this->smarty->display('produit_liste.tpl');
    }

    // Ajouter un produit
    public function add() {
        if (isset($_POST['Nom_produit'], $_POST['Prix_TTC'], $_POST['Stock'])) {
            $this->produitModel->ajouter($_POST['Nom_produit'], $_POST['Prix_TTC'], $_POST['Stock']);
            // Redirection vers la liste après ajout
            header("Location: index.php?action=produit");
            exit;
        } else {
            // Affiche le formulaire s'il n'a pas encore été soumis
            $this->smarty->display('produit_add.tpl');
        }
    }

    // Supprimer un produit
    public function delete() {
        $this->produitModel->delete($_GET['Id_produit']);
        // Rappeler la fonction pour afficher
        $this->liste();
    }

    // Modifier un produit
    public function modifier($Id_produit) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifie que les champs sont bien envoyés
            if (isset($_POST['Nom_produit'], $_POST['Prix_TTC'], $_POST['Stock'])) {
                $this->produitModel->modifier($_POST['Nom_produit'], $_POST['Prix_TTC'], $_POST['Stock'], $Id_produit);
                // Redirection après modification
                header("Location: index.php?action=produits");
                exit;
            }
        } else {
            // Affichage du formulaire avec les données existantes
            $produit = Produit::loadById($Id_produit);
            if ($produit) {
                // Passage des données à Smarty
                $this->smarty->assign('produit', [
                    'Id_produit' => $produit->getId(),
                    'Nom_produit' => $produit->getNom_produit(),
                    'Prix_TTC' => $produit->getPrix_TTC(),
                    'Stock' => $produit->getStock()
                ]);
                $this->smarty->display('produit_edit.tpl');
            }
        }
    }
}