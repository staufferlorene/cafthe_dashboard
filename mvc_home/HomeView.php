<?php

require_once 'init_smarty.php';

/**
 * Vue pour l'affichage des statistiques du tableau de bord
 *
 * Cette classe gère l'affichage de la page d'accueil avec les indicateurs
 * de performance via le moteur de template Smarty.
 */

class HomeView {
    private $smarty;

    /**
     * Constructeur : initialise Smarty et définit le répertoire des templates
     */
    public function __construct() {
        global $smarty;
        $this->smarty = $smarty;
        $this->smarty->setTemplateDir(__DIR__ . '/mvc_home');
    }

    /**
     * Affiche le tableau de bord avec toutes les statistiques
     *
     * @param array $ventesParProduits Liste des produits les plus vendus
     * @param array $ventesParCategories Montants totaux par catégorie
     * @param array $ventesParMois Évolution mensuelle du chiffre d'affaires
     * @param array $caParVendeur Chiffre d'affaires par vendeur
     *
     * @return void
     */
    public function afficherListe($ventesParProduits, $ventesParCategories, $ventesParMois, $caParVendeur) {
        $this->smarty->assign('ventesParProduits', $ventesParProduits);
        $this->smarty->assign('ventesParCategories', $ventesParCategories);
        $this->smarty->assign('ventesParMois', $ventesParMois);
        $this->smarty->assign('caParVendeur', $caParVendeur);

        $this->smarty->display('mvc_home\home_liste_view.tpl');
    }
}