<?php

require_once 'init_smarty.php';

class HomeView {
    private $smarty;

    public function __construct() {
        global $smarty;
        $this->smarty = $smarty;
        $this->smarty->setTemplateDir(__DIR__ . '/mvc_home');
    }

    /**
     * Affiche la liste des produits
     * @param array $produits Liste des produits à afficher
     */
    public function afficherListe($ventesParProduits, $ventesParCategories, $ventesParMois, $caParVendeur) {
        $this->smarty->assign('ventesParProduits', $ventesParProduits);
        $this->smarty->assign('ventesParCategories', $ventesParCategories);
        $this->smarty->assign('ventesParMois', $ventesParMois);
        $this->smarty->assign('caParVendeur', $caParVendeur);

        $this->smarty->display('mvc_home\home_liste_view.tpl');
    }
}