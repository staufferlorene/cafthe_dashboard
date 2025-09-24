<?php

require_once 'init_smarty.php';

class LoginView
{
    private $smarty;

    public function __construct() {
        global $smarty;
        $this->smarty = $smarty;
        $this->smarty->setTemplateDir(__DIR__ . '/mvc_login');
    }

    public function afficherFormulaireConnexion($erreur = null) {
        $this->smarty->assign('action', 'login');
        if ($erreur) {
            $this->smarty->assign('erreur', $erreur);
        }
        $this->smarty->display('mvc_login\login_form_view.tpl');
    }
}