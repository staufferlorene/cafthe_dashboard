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
    public function afficherListe($produits, $panier, $totalTTC) {
        // On récupère les données depuis la session
        $panier = $_SESSION['panier'] ?? [];
        $this->smarty->assign('panier', $panier);
        $this->smarty->assign('totalTTC', $totalTTC);
        $this->smarty->assign('produit', $produits);
        $this->smarty->display('mvc_panier\panier_liste_view.tpl');
    }

    public function afficherDetailPanier($panier, $totalHT, $totalTVA, $totalTTC) {
        // On récupère les données depuis la session
        $panier = $_SESSION['panier'];
        $this->smarty->assign('panier', $panier);
        $this->smarty->assign('totalHT', $totalHT);
        $this->smarty->assign('totalTVA', $totalTVA);
        $this->smarty->assign('totalTTC', $totalTTC);
        $this->smarty->display('mvc_panier\panier_detail_view.tpl');
    }

    public function afficherClient($clients) {
        $this->smarty->assign('client', $clients);
        $this->smarty->display('mvc_panier\panier_client_view.tpl');
    }
}