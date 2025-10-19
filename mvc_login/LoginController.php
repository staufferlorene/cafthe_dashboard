<?php

use mvc_login\LoginModel;

require_once 'mvc_login/LoginModel.php';
require_once 'mvc_login/LoginView.php';

/**
 * Contrôleur pour la gestion de la connexion
 *
 * Cette classe gère la logique métier entre le modèle {@see LoginModel}
 *  et la vue {@see LoginView}, dans le cadre du pattern MVC.
 *
 * Il permet de se connecter au dashboard.
 *
 */

class LoginController
{
    private $loginModel;
    private $loginView;

    /**
     * Constructeur : initialise le modèle
     */
    public function __construct()
    {
        $this->loginModel = new LoginModel();
        $this->loginView = new LoginView();
    }


    // COMMENTER LA FONCTION

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifie que les champs sont bien envoyés
            if (isset($_POST['mail'], $_POST['mdp'])) {

                // Retrait des espaces en début et fin chaîne
                $mail = trim($_POST['mail']);
                $mdp = trim($_POST['mdp']);

                // Vérifie que l'adresse mail est valide et que le mdp est non vide
                if (filter_var($mail, FILTER_VALIDATE_EMAIL) && !empty($mdp)) {

                    $utilisateur = $this->loginModel->getVendeurParMail($mail);

                    if ($utilisateur && $this->loginModel->verifierMotDePasse($mdp, $utilisateur['Mdp_vendeur'])) {
                        // Connexion réussie
                        $_SESSION['utilisateur'] = $utilisateur; // Stockage de l'utilisateur en session
                        header("Location: index.php");
                        exit();
                    } else {
                        $erreur = "Email ou mot de passe incorrect.";
                        $this->loginView->afficherFormulaireConnexion($erreur);
                    }
                } else {
                    $erreur = "Veuillez remplir correctement les champs.";
                    $this->loginView->afficherFormulaireConnexion($erreur);
                }
            } else {
                $this->loginView->afficherFormulaireConnexion();
            }
        } else {
            // Si la requête est GET -> afficher le formulaire
            $this->loginView->afficherFormulaireConnexion();
        }
    }

    public function logout() {
        // Détruit toutes les variables de session
        session_destroy();

        // Redirection vers la page de connexion
        header("Location: index.php?action=login");
        exit();
    }
}