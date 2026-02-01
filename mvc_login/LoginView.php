<?php

require_once 'init_smarty.php';

/**
 * Vue pour l'affichage du formulaire de connexion
 *
 * Cette classe gère l'affichage de la page de connexion
 * via le moteur de template Smarty.
 */

class LoginView {
    private $smarty;

    /**
     * Constructeur : initialise Smarty et définit le répertoire des templates
     */
    public function __construct() {
        global $smarty;
        $this->smarty = $smarty;
        $this->smarty->setTemplateDir(__DIR__ . '/mvc_login');
    }

    /**
     * Affiche le formulaire de connexion
     *
     * @param string|null $erreur Message d'erreur optionnel à afficher
     *
     * @return void
     */
    public function afficherFormulaireConnexion($erreur = null) {
        $this->smarty->assign('action', 'login');
        if ($erreur) {
            $this->smarty->assign('erreur', $erreur);
        }
        $this->smarty->display('mvc_login\login_form_view.tpl');
    }
}