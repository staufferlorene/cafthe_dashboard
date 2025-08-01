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
     */
    public function afficherFormulaireAjout($erreur = null) {
        $this->smarty->assign('action', 'add');
        if ($erreur) {
            $this->smarty->assign('erreur', $erreur);
        }
        $this->smarty->display('mvc_produit\produit_form_view.tpl');
    }

    /**
     * Affiche le formulaire de modification de produit
     * @param array $produit Données du produit à modifier
     * @param string|null $erreur Message d'erreur à afficher
     */
    public function afficherFormulaireModification($produit, $erreur = null) {
        $this->smarty->assign('action', 'update_produit');
        $this->smarty->assign('produit', $produit);
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
    public function afficherFormulaireModificationAvecDonnees($produit, $erreur = null) {
        $donneesProduit = [
            'Id_produit' => $produit->getId(),
            'Nom_produit' => $produit->getNom_produit(),
            'Prix_TTC' => $produit->getPrix_TTC(),
            'Stock' => $produit->getStock()
        ];

        $this->afficherFormulaireModification($donneesProduit, $erreur);
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
     * Affiche une page d'erreur personnalisée
     * @param string $message Message d'erreur à afficher
     * @param string $titre Titre de la page d'erreur
     */
    public function afficherErreur($message, $titre = 'Erreur') {
        $this->smarty->assign('titre', $titre);
        $this->smarty->assign('message', $message);
        $this->smarty->display('mvc_produit\erreur_view.tpl');
    }

    public function afficherFormulaireDetailAvecDonnees($produit, $erreur = null) {
        $donneesProduit = [
            'Id_produit' => $produit->getId(),
            'Nom_produit' => $produit->getNom_produit(),
            'Prix_TTC' => $produit->getPrix_TTC(),
            'Stock' => $produit->getStock(),
            'Description' => $produit->getDescription(),
        ];

        $this->afficherFormulaireDetail($donneesProduit, $erreur);
    }

    /**
     * Affiche le formulaire de modification de produit
     * @param array $produit Données du produit à modifier
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