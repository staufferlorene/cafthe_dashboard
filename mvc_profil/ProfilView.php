<?php

require_once 'init_smarty.php';

class ProfilView {

    private $smarty;

    public function __construct() {
        global $smarty;
        $this->smarty = $smarty;
        $this->smarty->setTemplateDir(__DIR__ . '/mvc_profil');
    }

    /**
     * Affiche le formulaire du détail d'un vendeur avec ses données
     * @param object $vendeur Objet vendeur avec les données existantes
     * @param string|null $erreur Message d'erreur à afficher
     */

    public function afficherFormulaireDetailAvecDonnees($vendeur, $erreur = null) {
        $donneesVendeur = [
            'Id_vendeur' => $vendeur->getId(),
            'Nom_vendeur' => $vendeur->getNom_vendeur(),
            'Prenom_vendeur' => $vendeur->getPrenom_vendeur(),
            'Mail_vendeur' =>$vendeur->getMail_vendeur(),
        ];

        $this->afficherFormulaireDetail($donneesVendeur, $erreur);
    }

    /**
     * Affiche le formulaire avec le détail du vendeur
     * @param array $vendeur Données du vendeur
     * @param string|null $erreur Message d'erreur à afficher
     */
    public function afficherFormulaireDetail($vendeur, $erreur = null) {
        $this->smarty->assign('action', 'profil');
        $this->smarty->assign('vendeur', $vendeur);
        if ($erreur) {
            $this->smarty->assign('erreur', $erreur);
        }
        $this->smarty->display('mvc_profil\profil_detail_view.tpl');
    }

    /**
     * Redirige vers le profil vendeur
     */
    public function redirigerVersProfil() {
        header("Location: index.php?action=profil");
        exit;
    }

    /**
     * Affiche le formulaire de modification avec les données d'un vendeur existant
     * @param object $vendeur Objet vendeur avec les données existantes
     * @param string|null $erreur Message d'erreur à afficher
     */
    public function afficherFormulaireModificationAvecDonnees($vendeur, $erreur = null) {
        $donneesVendeur = [
            'Id_vendeur' => $vendeur->getId(),
            'Nom_vendeur' => $vendeur->getNom_vendeur(),
            'Prenom_vendeur' => $vendeur->getPrenom_vendeur(),
            'Mail_vendeur' => $vendeur->getMail_vendeur(),
        ];

        $this->afficherFormulaireModification($donneesVendeur, $erreur);
    }

    /**
     * Affiche le formulaire de modification de vendeur
     * @param array $vendeur Données du vendeur à modifier
     * @param string|null $erreur Message d'erreur à afficher
     */
    public function afficherFormulaireModification($vendeur, $erreur = null) {
        $this->smarty->assign('action', 'update_profil');
        $this->smarty->assign('vendeur', $vendeur);
        if ($erreur) {
            $this->smarty->assign('erreur', $erreur);
        }
        $this->smarty->display('mvc_profil\profil_form_view.tpl');
    }

    /**
     * Affiche une erreur de vendeur introuvable
     */
    public function afficherErreurVendeurIntrouvable() {
        $this->smarty->assign('erreur', 'Vendeur introuvable.');
        $this->smarty->display('mvc_profil\profil_detail_view.tpl');
    }

    /**
     * Affiche le formulaire du détail d'un vendeur avec ses données
     * @param object $vendeur Objet vendeur avec les données existantes
     * @param string|null $erreur Message d'erreur à afficher
     */
    public function afficherFormulaireDetailAvecDonneesMdp($vendeur, $erreur = null) {
        $donneesVendeur = [
            'Id_vendeur' => $vendeur->getId(),
            'Nom_vendeur' => $vendeur->getNom_vendeur(),
            'Prenom_vendeur' => $vendeur->getPrenom_vendeur(),
            'Mail_vendeur' => $vendeur->getMail_vendeur(),
        ];

        $this->afficherProfilMdp($donneesVendeur, $erreur);
    }

    /**
     * Affiche le formulaire de modification de vendeur
     * @param array $vendeur Données du vendeur à modifier
     * @param string|null $erreur Message d'erreur à afficher
     */
    public function afficherProfilMdp($vendeur, $erreur = null) {
        $this->smarty->assign('action', 'update_profil');
        $this->smarty->assign('vendeur', $vendeur);
        if ($erreur) {
            $this->smarty->assign('erreur', $erreur);
        }
        $this->smarty->display('mvc_profil\profil_detail_view.tpl');
    }
}