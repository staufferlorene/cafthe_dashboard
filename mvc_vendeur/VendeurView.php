<?php

require_once 'init_smarty.php';

/**
 * Vue pour l'affichage des vendeurs
 *
 * Cette classe gère l'affichage des différentes vues liées aux vendeurs
 * via le moteur de template Smarty.
 */

class VendeurView {
    private $smarty;

    /**
     * Constructeur : initialise Smarty et définit le répertoire des templates
     */
    public function __construct() {
        global $smarty;
        $this->smarty = $smarty;
        $this->smarty->setTemplateDir(__DIR__ . '/mvc_vendeur');
    }

    /**
     * Affiche la liste des vendeurs
     *
     * @param array $vendeurs Liste des vendeurs à afficher
     *
     * @return void
     */
    public function afficherListe($vendeurs) {
        $this->smarty->assign('vendeur', $vendeurs);
        $this->smarty->display('mvc_vendeur\vendeur_liste_view.tpl');
    }

    /**
     * Affiche le formulaire d'ajout de vendeur
     *
     * @param string|null $erreur Message d'erreur à afficher
     * @param array $role Liste des rôles disponibles
     *
     * @return void
     */
    public function afficherFormulaireAjout($erreur = null, $role = []) {
        $this->smarty->assign('action', 'add');
        $this->smarty->assign('role', $role);
        if ($erreur) {
            $this->smarty->assign('erreur', $erreur);
        }
        $this->smarty->display('mvc_vendeur\vendeur_form_view.tpl');
    }

    /**
     * Affiche le formulaire de modification de vendeur
     *
     * @param array $vendeur Données du vendeur à modifier
     * @param array $role Liste des rôles disponibles
     * @param string|null $erreur Message d'erreur à afficher
     *
     * @return void
     */
    public function afficherFormulaireModification($vendeur, $role = [], $erreur = null) {
        $this->smarty->assign('action', 'update_vendeur');
        $this->smarty->assign('vendeur', $vendeur);
        $this->smarty->assign('role', $role);
        if ($erreur) {
            $this->smarty->assign('erreur', $erreur);
        }
        $this->smarty->display('mvc_vendeur\vendeur_form_view.tpl');
    }

    /**
     * Affiche le formulaire de modification avec les données d'un vendeur existant
     *
     * @param Object $vendeur Objet vendeur avec les données existantes
     * @param array $role Liste des rôles disponibles
     * @param string|null $erreur Message d'erreur à afficher
     *
     * @return void
     */
    public function afficherFormulaireModificationAvecDonnees($vendeur, $role, $erreur = null) {
        $donneesVendeur = [
            'Id_vendeur' => $vendeur->getId(),
            'Nom_vendeur' => $vendeur->getNom_vendeur(),
            'Prenom_vendeur' => $vendeur->getPrenom_vendeur(),
            'Role' => $vendeur->getRole(),
            'Mail_vendeur' => $vendeur->getMail_vendeur(),
        ];

        $this->afficherFormulaireModification($donneesVendeur, $role, $erreur);
    }

    /**
     * Affiche une erreur lorsque le vendeur n'est pas trouvé
     *
     * @return void
     */
    public function afficherErreurVendeurIntrouvable() {
        $this->smarty->assign('erreur', 'Vendeur introuvable.');
        $this->smarty->display('mvc_vendeur\vendeur_form_view.tpl');
    }

    /**
     * Redirige vers la liste des vendeurs
     *
     * @return void
     */
    public function redirigerVersListe() {
        header("Location: index.php?action=vendeur");
        exit;
    }

    /**
     * Affiche le formulaire du détail d'un vendeur avec ses données
     *
     * @param Object $vendeur Objet vendeur avec les données existantes
     * @param string|null $erreur Message d'erreur à afficher
     *
     * @return void
     */

    public function afficherFormulaireDetailAvecDonnees($vendeur, $erreur = null) {
        $donneesVendeur = [
            'Id_vendeur' => $vendeur->getId(),
            'Nom_vendeur' => $vendeur->getNom_vendeur(),
            'Prenom_vendeur' => $vendeur->getPrenom_vendeur(),
            'Role' => $vendeur->getRole(),
            'Mail_vendeur' =>$vendeur->getMail_vendeur(),
        ];

        $this->afficherFormulaireDetail($donneesVendeur, $erreur);
    }

    /**
     * Affiche le formulaire avec le détail du vendeur
     *
     * @param array $vendeur Données du vendeur
     * @param string|null $erreur Message d'erreur à afficher
     *
     * @return void
     */
    public function afficherFormulaireDetail($vendeur, $erreur = null) {
        $this->smarty->assign('action', 'detail_vendeur');
        $this->smarty->assign('vendeur', $vendeur);
        if ($erreur) {
            $this->smarty->assign('erreur', $erreur);
        }
        $this->smarty->display('mvc_vendeur\vendeur_detail_view.tpl');
    }
}