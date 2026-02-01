<?php

require_once 'init_smarty.php';

/**
 * Vue pour l'affichage du profil vendeur
 *
 * Cette classe gère l'affichage des différentes vues liées au profil
 * du vendeur via le moteur de template Smarty.
 */

class ProfilView {

    private $smarty;

    /**
     * Constructeur : initialise Smarty et définit le répertoire des templates
     */
    public function __construct() {
        global $smarty;
        $this->smarty = $smarty;
        $this->smarty->setTemplateDir(__DIR__ . '/mvc_profil');
    }

    /**
     * Affiche le formulaire du détail d'un vendeur avec ses données
     *
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
     *
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
     *
     * @return void
     */
    public function redirigerVersProfil() {
        header("Location: index.php?action=profil");
        exit;
    }

    /**
     * Affiche le formulaire de modification avec les données d'un vendeur existant
     *
     * @param object $vendeur Objet vendeur avec les données existantes
     * @param string|null $erreur Message d'erreur à afficher
     *
     * @return void
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
     *
     * @param array $vendeur Données du vendeur à modifier
     * @param string|null $erreur Message d'erreur à afficher
     *
     * @return void
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
     *
     * @return void
     */
    public function afficherErreurVendeurIntrouvable() {
        $this->smarty->assign('erreur', 'Vendeur introuvable.');
        $this->smarty->display('mvc_profil\profil_detail_view.tpl');
    }

    /**
     * Affiche le formulaire du profil avec un message d'erreur lié au mot de passe
     *
     * @param ProfilModel $vendeur Objet vendeur avec les données existantes
     * @param string|null $erreur Message d'erreur à afficher (lié au changement de mot de passe)
     *
     * @return void
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
     * Affiche le profil du vendeur avec un message d'erreur
     *
     * @param array $vendeur Données du vendeur
     * @param string|null $erreur Message d'erreur à afficher
     *
     * @return void
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