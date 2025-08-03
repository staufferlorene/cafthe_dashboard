<?php

use mvc_produit\ProduitModel;

require_once 'mvc_produit/ProduitModel.php';
require_once 'mvc_produit/ProduitView.php';

class ProduitController {
    private $produitModel;
    private $produitView;

    public function __construct() {
        $this->produitModel = new ProduitModel();
        $this->produitView = new ProduitView();
    }

    // Afficher les produits
    public function liste() {
        $produits = $this->produitModel->lister();
        $this->produitView->afficherListe($produits);
    }

    // Ajouter un produit
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifie que les champs sont bien envoyés
            if (isset($_POST['nom'], $_POST['description'], $_POST['prix_ttc'], $_POST['prix_ht'], $_POST['stock'], $_POST['conditionnement'], $_POST['categorie'])) {
                // Tente l'ajout
                $erreur = $this->produitModel->ajouter($_POST['nom'], $_POST['description'], $_POST['prix_ttc'], $_POST['prix_ht'], $_POST['stock'], $_POST['conditionnement'], $_POST['categorie']);

                if ($erreur === null) {
                    // Si succès : Redirection vers la liste après ajout
                    $this->produitView->redirigerVersListe();
                } else {
                    // Si échec : Affiche le formulaire avec message d'erreur
                    $categories = ProduitModel::categories();
                    $this->produitView->afficherFormulaireAjout($erreur, $categories);
                }
            }
        } else {
            // Affiche le formulaire vide
            $categories = ProduitModel::categories();
            $this->produitView->afficherFormulaireAjout(null, $categories);
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
            if (isset($_POST['nom'], $_POST['description'], $_POST['prix_ttc'], $_POST['prix_ht'], $_POST['stock'], $_POST['conditionnement'], $_POST['categorie'])) {
                // Tente la modification
                $erreur = $this->produitModel->modifier($_POST['nom'], $_POST['description'], $_POST['prix_ttc'], $_POST['prix_ht'], $_POST['stock'], $_POST['conditionnement'], $_POST['categorie'], $Id_produit);
                if ($erreur === null) {
                    // Si succès : Redirection vers la liste après modification
                    $this->produitView->redirigerVersListe();
                } else {
                    // Si échec : Affiche le formulaire avec message d'erreur
                    $produit = ProduitModel::loadById($Id_produit);
                    if ($produit) {
                        $this->produitView->afficherFormulaireModificationAvecDonnees($produit, $erreur);
                    } else {
                        $this->produitView->afficherErreurProduitIntrouvable();
                    }
                }
            }
        } else {
            // Affichage du formulaire avec les données existantes
            $produit = ProduitModel::loadById($Id_produit);
            if ($produit) {
                $this->produitView->afficherFormulaireModificationAvecDonnees($produit);
            } else {
                $this->produitView->afficherErreurProduitIntrouvable();
            }
        }
    }

    // Voir le détail d'un produit
    public function voirDetail($Id_produit) {
        // Affichage du formulaire avec les données existantes
        $produit = ProduitModel::loadById($Id_produit);
        if ($produit) {
            $this->produitView->afficherFormulaireDetailAvecDonnees($produit);
        } else {
            $this->produitView->afficherErreurProduitIntrouvable();
        }
    }
}