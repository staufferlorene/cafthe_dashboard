<?php

require_once 'init_smarty.php';

/**
 * Vue pour l'affichage du panier d'achat
 *
 * Cette classe gère l'affichage des différentes vues liées au panier
 * via le moteur de template Smarty.
 */

class PanierView {
    private $smarty;

    /**
     * Constructeur : initialise Smarty et définit le répertoire des templates
     */
    public function __construct() {
        global $smarty;
        $this->smarty = $smarty;
        $this->smarty->setTemplateDir(__DIR__ . '/mvc_panier');
    }

    /**
     * Affiche la liste des produits disponibles avec le panier et son total
     *
     * @param array $produits Liste des produits
     * @param array $panier Contenu actuel du panier
     * @param float $totalTTC Montant total TTC du panier
     *
     * @return void
     */
    public function afficherListe($produits, $panier, $totalTTC) {
        // On récupère les données depuis la session
        $panier = $_SESSION['panier'] ?? [];
        $this->smarty->assign('panier', $panier);
        $this->smarty->assign('totalTTC', $totalTTC);
        $this->smarty->assign('produit', $produits);
        $this->smarty->display('mvc_panier\panier_liste_view.tpl');
    }

    /**
     * Affiche le détail du panier avec les totaux
     *
     * @param array $panier Contenu du panier
     * @param float $totalHT Montant total HT
     * @param float $totalTVA Montant total de la TVA
     * @param float $totalTTC Montant total TTC
     *
     * @return void
     */
    public function afficherDetailPanier($panier, $totalHT, $totalTVA, $totalTTC) {
        // On récupère les données depuis la session
        $panier = $_SESSION['panier'];
        $this->smarty->assign('panier', $panier);
        $this->smarty->assign('totalHT', $totalHT);
        $this->smarty->assign('totalTVA', $totalTVA);
        $this->smarty->assign('totalTTC', $totalTTC);
        $this->smarty->display('mvc_panier\panier_detail_view.tpl');
    }

    /**
     * Affiche la liste des clients pour sélection lors de la validation du panier
     *
     * @param array $clients Liste des clients
     *
     * @return void
     */
    public function afficherClient($clients) {
        $this->smarty->assign('client', $clients);
        $this->smarty->display('mvc_panier\panier_client_view.tpl');
    }

    /**
     * Affiche le récapitulatif complet du panier avant paiement
     *
     * Récupère et affiche :
     * - Le contenu du panier
     * - Les montants (HT, TVA, TTC)
     * - Les informations du client sélectionné
     *
     * @return void
     */
    public function afficherRecapitulatifPanier() {
        // On récupère les données depuis la session
        $panier = $_SESSION['panier'];
        $montants = $_SESSION['montants'];
        $clientSession = $_SESSION['clientSession'];

        $this->smarty->assign('panier', $panier);
        $this->smarty->assign('montants', $montants);
        $this->smarty->assign('clientSession', $clientSession);

        $this->smarty->display('mvc_panier\panier_recapitulatif_view.tpl');
    }

    /**
     * Affiche la page de confirmation après paiement
     *
     * @return void
     */
    public function panierPaye($erreur = null) {
        if ($erreur) {
            $this->smarty->assign('erreur', $erreur);
        }
        $this->smarty->display('mvc_panier\panier_paye_view.tpl');
    }
}