<?php

use mvc_home\HomeModel;

require_once 'mvc_home/HomeModel.php';
require_once 'mvc_home/HomeView.php';

/**
 * Contrôleur pour la gestion des statistiques
 *
 * Cette classe gère la logique métier entre le modèle {@see HomeModel}
 * et la vue {@see HomeView}, dans le cadre du pattern MVC.
 *
 * Elle permet de :
 * - afficher les produits les plus vendus
 * - afficher les ventes par catégorie
 * - afficher l'évolution des ventes mensuelles
 * - afficher le chiffre d'affaires par vendeur
 */

class HomeController {
    private $homeModel;
    private $homeView;

    /**
     * Constructeur : initialise le modèle et la vue associés aux statistiques
     */
    public function __construct() {
        $this->homeModel = new HomeModel();
        $this->homeView = new HomeView();
    }

    /**
     * Affiche le tableau de bord avec l'ensemble des statistiques
     *
     * Récupère et affiche :
     * - Les 6 produits les plus vendus
     * - Les ventes par catégorie
     * - L'évolution mensuelle du chiffre d'affaires
     * - Le classement des vendeurs par CA
     *
     * @return void
     */
    public function afficherStat() {
        $ventesParProduits = $this->homeModel->listerProduitsVendus();
        $ventesParCategories = $this->homeModel->listerVentesParCategories();
        $ventesParMois = $this->homeModel->listerVentesParMois();
        $caParVendeur = $this->homeModel->listerCAParVendeur();

        $this->homeView->afficherListe($ventesParProduits, $ventesParCategories, $ventesParMois, $caParVendeur);
    }
}