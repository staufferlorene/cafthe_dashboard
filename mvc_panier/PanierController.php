<?php

use mvc_panier\PanierModel;

require_once 'mvc_panier/PanierModel.php';
require_once 'mvc_panier/PanierView.php';

/**
 * Contrôleur pour la gestion du panier
 *
 * Cette classe gère la logique métier entre le modèle {@see PanierModel}
 * et la vue {@see PanierView}, dans le cadre du pattern MVC.
 *
 * Elle permet de :
 * - lister les produits
 * - ajouter un produit
 * - modifier un produit
 * - supprimer un produit
 * - afficher le détail d’un produit
 */

class PanierController {
    private $panierModel;
    private $panierView;

    /**
     * Constructeur : initialise le modèle et la vue associés aux produits
     */
    public function __construct() {
        $this->panierModel = new PanierModel();
        $this->panierView = new PanierView();
    }

    /**
     * Affiche la liste de tous les produits
     *
     * @return void
     */
    public function liste() {
        $produits = $this->panierModel->lister();
        $this->panierView->afficherListe($produits);
    }

    public function ajoutPanier() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifie que les champs sont bien envoyés
            if (isset($_POST['id'], $_POST['nom'], $_POST['prixht'], $_POST['prixttc'], $_POST['quantite'])) {
                // Assignation des variables
                $id = $_POST['id'];
                $nom = $_POST['nom'];
                $prixht = $_POST['prixht'];
                $prixttc = $_POST['prixttc'];
                $quantite = $_POST['quantite'];
            }

            // Si le panier n'existe pas on le crée et initialise avec un tableau vide
            if (!isset($_SESSION['panier'])) {
                $_SESSION['panier'] = [];
            }

            // Si le produit n'existe pas dans le panier on le crée
            if (!isset($_SESSION['panier'][$id])) {
                $_SESSION['panier'][$id] = [
                    'id' => $id,
                    'nom' => $nom,
                    'prixht' => $prixht,
                    'prixttc' => $prixttc,
                    'quantite' => $quantite
                ];
            } else {
                // Si le produit existe déjà on màj la quantité
                $_SESSION['panier'][$id]['quantite'] += (int)$quantite;
            }
        }
    }

    public function voirPanier() {

        $panier = $_SESSION['panier'];

        $totalHT = 0;
        $totalTTC = 0;

        // Calcul des totaux HT et TTC sur tous les produits du panier
        foreach ($panier as $produit) {
            $totalHT += $produit['prixht'] * $produit['quantite'];
            $totalTTC += $produit['prixttc'] * $produit['quantite'];
        }

        // Calcul de la TVA
        $totalTVA = $totalTTC - $totalHT;

        $this->panierView->afficherDetailPanier($panier, $totalHT, $totalTVA, $totalTTC);
    }

    public function delete() {
        $this->panierModel->delete($_GET['id']);
        // Rappeler la fonction pour afficher le panier
        $this->voirPanier();
    }
}