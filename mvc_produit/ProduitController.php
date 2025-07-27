<?php

use mvc_produit\ProduitModel;

require_once 'mvc_produit/ProduitModel.php';
require_once 'init_smarty.php';

class ProduitController
{
    private $produitModel;
    private $smarty;

    public function __construct() {
        $this->produitModel = new ProduitModel();
        global $smarty;
        $this->smarty = $smarty;
        $this->smarty->setTemplateDir(__DIR__ . '/mvc_produit');
    }

    // Afficher les produits
    public function liste() {
        $produit =$this->produitModel->lister();
        // On assigne les variables à smarty
        $this->smarty->assign('produit', $produit);
        $this->smarty->display('mvc_produit\produit_liste_view.tpl');
    }

    // Ajouter un produit

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifie que les champs sont bien envoyés
            if (isset($_POST['nom'], $_POST['prix'], $_POST['stock'])) {
                // Tente l'ajout
                $erreur = $this->produitModel->ajouter($_POST['nom'], $_POST['prix'], $_POST['stock']);

                if ($erreur === null) {
                    // Si succès : Redirection vers la liste après ajout
                    header("Location: index.php?action=produit");
                    exit;
                } else {
                    // Si échec : Affiche le formulaire avec message d'erreur
                    $this->smarty->assign('erreur', $erreur);
                    $this->smarty->display('mvc_produit\produit_add_view.tpl');
                }
            }
        } else {
            // Affiche le formulaire vide
            $this->smarty->display('mvc_produit\produit_add_view.tpl');
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
            $produit = ProduitModel::loadById($Id_produit);
            if ($produit) {
                // Passage des données à Smarty
                $this->smarty->assign('produit', [
                    'Id_produit' => $produit->getId(),
                    'Nom_produit' => $produit->getNom_produit(),
                    'Prix_TTC' => $produit->getPrix_TTC(),
                    'Stock' => $produit->getStock()
                ]);
                $this->smarty->display('mvc_produit\produit_edit_view.tpl');
            }
        }
    }
}