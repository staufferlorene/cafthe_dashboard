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
}