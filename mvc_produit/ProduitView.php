<?php

require_once 'init_smarty.php';

class ProduitView {
    private $smarty;

    public function __construct() {
        global $smarty;
        $this->smarty = $smarty;
        $this->smarty->setTemplateDir(__DIR__ . '/mvc_produit');
    }

    /**
     * Affiche la liste des produits
     * @param array $produits Liste des produits à afficher
     */
    public function afficherListe($produits) {
        $this->smarty->assign('produit', $produits);
        $this->smarty->display('mvc_produit\produit_liste_view.tpl');
    }

    /**
     * Affiche le formulaire d'ajout de produit
     * @param string|null $erreur Message d'erreur à afficher
     * @param array $categories Catégorie de produit provenant de la BDD
     */
    public function afficherFormulaireAjout($erreur = null, $categories = [], $conditionnements = []) {
        $this->smarty->assign('action', 'add');
        $this->smarty->assign('categories', $categories);
        $this->smarty->assign('conditionnements', $conditionnements);
        if ($erreur) {
            $this->smarty->assign('erreur', $erreur);
        }
        $this->smarty->display('mvc_produit\produit_form_view.tpl');
    }

    /**
     * Affiche le formulaire de modification de produit
     * @param array $produit Données du produit à modifier
     * @param string|null $erreur Message d'erreur à afficher
     * @param array $categories Catégorie de produit provenant de la BDD
     */
    public function afficherFormulaireModification($produit, $erreur = null, $categories = [], $conditionnements = []) {
        $this->smarty->assign('action', 'update_produit');
        $this->smarty->assign('produit', $produit);
        $this->smarty->assign('categories', $categories);
        $this->smarty->assign('conditionnements', $conditionnements);
        if ($erreur) {
            $this->smarty->assign('erreur', $erreur);
        }
        $this->smarty->display('mvc_produit/produit_form_view.tpl');
    }

    /**
     * Affiche le formulaire de modification avec les données d'un produit existant
     * @param object $produit Objet produit avec les données existantes
     * @param string|null $erreur Message d'erreur à afficher
     */
    public function afficherFormulaireModificationAvecDonnees($produit, $categorie, $conditionnements, $erreur = null) {
        $donneesProduit = [
            'Id_produit' => $produit->getId(),
            'Nom_produit' => $produit->getNom_produit(),
            'Description' => $produit->getDescription(),
            'Prix_TTC' => $produit->getPrix_TTC(),
            'Prix_HT' => $produit->getPrix_HT(),
            'Stock' => $produit->getStock(),
            'Type_conditionnement' => $produit->getType_conditionnement(),
            'Nom_categorie' => $produit->getNom_categorie(),
            'Id_categorie' => $produit->getId_categorie(),
            'Tva_categorie' => $produit->getTva_categorie()
        ];

        $this->afficherFormulaireModification($donneesProduit, $erreur, $categorie, $conditionnements);
    }

    /**
     * Affiche une erreur de produit introuvable
     */
    public function afficherErreurProduitIntrouvable() {
        $this->smarty->assign('erreur', 'Produit introuvable.');
        $this->smarty->display('mvc_produit\produit_form_view.tpl');
    }

    /**
     * Redirige vers la liste des produits
     */
    public function redirigerVersListe() {
        header("Location: index.php?action=produit");
        exit;
    }

    /**
     * Affiche le formulaire du détail d'un produit avec ses données
     * @param object $produit Objet produit avec les données existantes
     * @param string|null $erreur Message d'erreur à afficher
     */

    public function afficherFormulaireDetailAvecDonnees($produit, $erreur = null) {
        $donneesProduit = [
            'Id_produit' => $produit->getId(),
            'Nom_produit' => $produit->getNom_produit(),
            'Description' => $produit->getDescription(),
            'Prix_TTC' => $produit->getPrix_TTC(),
            'Prix_HT' =>$produit->getPrix_HT(),
            'Tva_categorie'=>$produit->getTva_categorie(),
            'Stock' => $produit->getStock(),
            'Nom_categorie' => $produit->getNom_categorie(),
            'Type_conditionnement' => $produit->getType_conditionnement()
        ];

        $this->afficherFormulaireDetail($donneesProduit, $erreur);
    }

    /**
     * Affiche le formulaire avec le détail du produit
     * @param array $produit Données du produit
     * @param string|null $erreur Message d'erreur à afficher
     */
    public function afficherFormulaireDetail($produit, $erreur = null) {
        $this->smarty->assign('action', 'detail_produit');
        $this->smarty->assign('produit', $produit);
        if ($erreur) {
            $this->smarty->assign('erreur', $erreur);
        }
        $this->smarty->display('mvc_produit/produit_detail_view.tpl');
    }
}