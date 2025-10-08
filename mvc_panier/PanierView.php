<?php

require_once 'init_smarty.php';

class PanierView {
    private $smarty;

    public function __construct() {
        global $smarty;
        $this->smarty = $smarty;
        $this->smarty->setTemplateDir(__DIR__ . '/mvc_panier');
    }

    /**
     * Affiche la liste des produits
     * @param array $produits Liste des produits à afficher
     */
    public function afficherListe($produits) {
        $this->smarty->assign('produit', $produits);
        $this->smarty->display('mvc_panier\panier_liste_view.tpl');
    }
}