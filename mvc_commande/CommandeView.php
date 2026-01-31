<?php

require_once 'init_smarty.php';

class CommandeView {
    private $smarty;

    public function __construct() {
        global $smarty;
        $this->smarty = $smarty;
        $this->smarty->setTemplateDir(__DIR__ . '/mvc_commande');
    }

    /**
     * Affiche la liste des commandes
     * @param array $commandes Liste des commandes à afficher
     */
    public function afficherListe($commandes) {
        $this->smarty->assign('commande', $commandes);
        $this->smarty->display('mvc_commande\commande_liste_view.tpl');
    }

    /**
     * Affiche le formulaire d'ajout de produit
     * @param string|null $erreur Message d'erreur à afficher
     */
    public function afficherFormulaireAjout($erreur = null) {
        $this->smarty->assign('action', 'add');
        if ($erreur) {
            $this->smarty->assign('erreur', $erreur);
        }
        $this->smarty->display('mvc_client\client_form_view.tpl');
    }

    /**
     * Affiche le formulaire de modification de commande
     * @param array $commande Données de la commande à modifier
     * @param string|null $erreur Message d'erreur à afficher
     */
    public function afficherFormulaireModification($commande, $produit, $statutsCommandes = [], $erreur = null) {
        $this->smarty->assign('action', 'update_client');
        $this->smarty->assign('commande', $commande);
        $this->smarty->assign('produit', $produit);
        $this->smarty->assign('statutsCommandes', $statutsCommandes);
        if ($erreur) {
            $this->smarty->assign('erreur', $erreur);
        }
        $this->smarty->display('mvc_commande/commande_form_view.tpl');
    }

    /**
     * Affiche le formulaire de modification avec les données d'une commande existante
     * @param object $commande Objet commande avec les données existantes
     * @param string|null $erreur Message d'erreur à afficher
     */
    public function afficherFormulaireModificationAvecDonnees($commande, $produit, $statutsCommandes, $erreur = null) {
        $donneesCommande = [
            'Id_commande' => $commande->getId(),
            'Nom_client' => $commande->getNom_client(),
            'Prenom_client' => $commande->getPrenom_client(),
            'Adresse_livraison' => $commande->getAdresse_livraison(),
            'Date_commande' => $commande->getDate_commande(),
            'Statut_commande' =>$commande->getStatut_commande(),
            'Montant_commande_TTC' =>$commande->getMontant_commande_TTC(),
            'Nom_produit' => $commande->getNom_produit(),
            'Quantite_produit_ligne_commande' => $commande->getQuantite_produit_ligne_commande(),
            'Montant_commande_HT' => $commande->getMontant_commande_HT(),
            'Montant_TVA' => $commande->getMontant_TVA(),
        ];

        $this->afficherFormulaireModification($donneesCommande, $produit, $statutsCommandes, $erreur);
    }

    /**
     * Affiche une erreur de commande introuvable
     */
    public function afficherErreurCommandeIntrouvable() {
        $this->smarty->assign('erreur', 'Commande introuvable.');
        $this->smarty->display('mvc_commande\commande_form_view.tpl');
    }

    /**
     * Redirige vers la liste des commandes
     */
    public function redirigerVersListe() {
        header("Location: index.php?action=commande");
        exit;
    }

    /**
     * Affiche le formulaire du détail d'une commande avec ses données
     * @param object $commande Objet commande avec les données existantes
     * @param string|null $erreur Message d'erreur à afficher
     */
    public function afficherFormulaireDetailAvecDonnees($commande, $produit, $erreur = null) {
        $donneesCommande = [
            'Id_commande' => $commande->getId(),
            'Nom_client' => $commande->getNom_client(),
            'Prenom_client' => $commande->getPrenom_client(),
            'Adresse_livraison' => $commande->getAdresse_livraison(),
            'Date_commande' => $commande->getDate_commande(),
            'Statut_commande' =>$commande->getStatut_commande(),
            'Montant_commande_TTC' =>$commande->getMontant_commande_TTC(),
            'Nom_produit' => $commande->getNom_produit(),
            'Quantite_produit_ligne_commande' => $commande->getQuantite_produit_ligne_commande(),
            'Montant_commande_HT' => $commande->getMontant_commande_HT(),
            'Montant_TVA' => $commande->getMontant_TVA(),
        ];

        $this->afficherFormulaireDetail($donneesCommande, $produit, $erreur);
    }

    /**
     * Affiche le formulaire de modification de commande
     * @param array $commande Données de la commande à modifier
     * @param string|null $erreur Message d'erreur à afficher
     */
    public function afficherFormulaireDetail($commande, $produit, $erreur = null) {
        $this->smarty->assign('action', 'detail_commande');
        $this->smarty->assign('commande', $commande);
        $this->smarty->assign('produit', $produit);
        if ($erreur) {
            $this->smarty->assign('erreur', $erreur);
        }
        $this->smarty->display('mvc_commande/commande_detail_view.tpl');
    }
}