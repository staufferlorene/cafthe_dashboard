<?php

use mvc_produit\ProduitModel;

require_once 'mvc_produit/ProduitModel.php';
require_once 'mvc_produit/ProduitView.php';

/**
 * Contrôleur pour la gestion des produits
 *
 * Cette classe gère la logique métier entre le modèle {@see ProduitModel}
 * et la vue {@see ProduitView}, dans le cadre du pattern MVC.
 *
 * Elle permet de :
 * - lister les produits
 * - ajouter un produit
 * - modifier un produit
 * - supprimer un produit
 * - afficher le détail d’un produit
 */

class ProduitController {
    private $produitModel;
    private $produitView;

    /**
     * Constructeur : initialise le modèle et la vue associés aux produits
     */
    public function __construct() {
        $this->produitModel = new ProduitModel();
        $this->produitView = new ProduitView();
    }

    /**
     * Affiche la liste de tous les produits
     *
     * @return void
     */
    public function liste() {
        $produits = $this->produitModel->lister();

        // Vérifie si au moins une ligne de commande est rattachée au produit
        foreach ($produits as &$produit) {
            $produit['haveOrder'] = ProduitModel::haveOrder($produit['Id_produit']);
        }

        $this->produitView->afficherListe($produits);
    }

    /**
     * Ajoute un nouveau produit
     *
     * Si la requête est en POST et valide, tente l’ajout en base de données :
     *   - redirige vers la liste si succès,
     *   - affiche le formulaire avec un message d’erreur si échec.
     * Sinon affiche le formulaire d’ajout vide
     *
     * @return void
     */
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
                    $conditionnements = ProduitModel::conditionnements();
                    $this->produitView->afficherFormulaireAjout($erreur, $categories, $conditionnements);
                }
            }
        } else {
            // Affiche le formulaire vide
            $categories = ProduitModel::categories();
            $conditionnements = ProduitModel::conditionnements();
            $this->produitView->afficherFormulaireAjout(null, $categories, $conditionnements);
        }
    }

    /**
     * Supprime un produit à partir de son identifiant (passé en GET)
     *
     * @param int $Id_produit Identifiant du produit (via $_GET['Id_produit'])
     *
     * @return void
     */
    public function delete() {
        $this->produitModel->delete($_GET['Id_produit']);
        // Rappeler la fonction pour afficher
        $this->liste();
    }

    /**
     *  Modifie un produit existant
     *
     *  Si la requête est en POST et valide, tente la modification :
     *    - redirige vers la liste si succès,
     *    - affiche le formulaire de modification avec erreurs si échec.
     *  Si la requête est en GET :
     *   - affiche le formulaire prérempli si le produit existe,
     *   - sinon affiche une erreur.
     *
     * @param int $Id_produit Identifiant du produit à modifier
     *
     * @return void
     */
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
                    $categorie = ProduitModel::categories();
                    $conditionnements = ProduitModel::conditionnements();
                    if ($produit) {
                        $this->produitView->afficherFormulaireModificationAvecDonnees($produit, $categorie, $conditionnements, $erreur);
                    } else {
                        $this->produitView->afficherErreurProduitIntrouvable();
                    }
                }
            }
        } else {
            // Affichage du formulaire avec les données existantes
            $produit = ProduitModel::loadById($Id_produit);
            $categorie = ProduitModel::categories();
            $conditionnements = ProduitModel::conditionnements();
            if ($produit) {
                $this->produitView->afficherFormulaireModificationAvecDonnees($produit, $categorie, $conditionnements);
            } else {
                $this->produitView->afficherErreurProduitIntrouvable();
            }
        }
    }

    /**
     * Affiche le détail d’un produit existant
     *
     * - Si le produit est trouvé, affiche le formulaire de détail,
     * - Sinon affiche une erreur "introuvable"
     *
     * @param int $Id_produit Identifiant du produit
     *
     * @return void
     */
    public function voirDetail($Id_produit) {
        // Affichage du formulaire avec les données existantes
        $produit = ProduitModel::loadById($Id_produit);
        if ($produit) {
            $this->produitView->afficherFormulaireDetailAvecDonnees($produit);
        } else {
            $this->produitView->afficherErreurProduitIntrouvable();
        }
    }

    /**
     * Charge les données pour la TopBar (alertes de stock)
     *
     * @return void
     */
    public function loadTopBarData() {
        $alertesStock = ProduitModel::getStockAlert();
        if ($alertesStock) {
            $this->produitView->afficherAlerteStockBas($alertesStock);
        }
    }
}