<?php

// 2 lignes ci-dessous permettent d'afficher les erreurs PHP
error_reporting(E_ALL);
ini_set("display_errors", 1);

/**
 * Front controller
 *
 * Point d'entrée de l'application
 * Il lit les paramètres passés dans l'URL
 * Selon ces paramètres, il instancie le contrôleur qui convient
 */

// Démarrage de la session
session_start();

// Inclusion des contrôleurs
require_once 'init_smarty.php';
require_once 'mvc_produit/ProduitController.php';
require_once 'mvc_client/ClientController.php';
require_once 'mvc_commande/CommandeController.php';
require_once 'mvc_vendeur/VendeurController.php';
require_once 'mvc_login/LoginController.php';
require_once 'mvc_profil/ProfilController.php';
require_once 'mvc_panier/PanierController.php';

// Vérification si l'utilisateur est connecté
if (isset($_SESSION['utilisateur'])) {
    // Passage des infos de l'utilisateur à smarty
    $smarty->assign('utilisateur', $_SESSION['utilisateur']);
    // Récupération des paramètres de l'action via l'URL, si pas d'action affichage de la page d'accueil
    $action = isset($_GET['action']) ? $_GET['action'] : 'produit';
    // Si utilisateur non connecté affichage de la page de connexion
} else {
    $action = 'login';
}

// Récupération de l'id du produit
$Id_produit = isset($_GET['Id_produit']) ? intval($_GET['Id_produit']) : 0;

// Récupération de l'id du client
$Id_client = isset($_GET['Id_client']) ? intval($_GET['Id_client']) : 0;

// Récupération de l'id de la commande
$Id_commande = isset($_GET['Id_commande']) ? intval($_GET['Id_commande']) : 0;

// Récupération de l'id du vendeur
$Id_vendeur = isset($_GET['Id_vendeur']) ? intval($_GET['Id_vendeur']) : 0;


switch ($action) {

    /*********************
     *********************
     *
     * Pour la connexion
     *
     *********************
     ********************/

    case 'login':
        // Appel de la méthode pour se connecter
        $controller = new LoginController();
        $controller->login();
        break;

    /*********************
     *********************
     *
     * Pour la déconnexion
     *
     *********************
     ********************/

    case 'logout':
        // Appel de la méthode pour se déconnecter
        $controller = new LoginController();
        $controller->logout();
        break;

    /*********************
     *********************
     *
     * Pour les produits
     *
     *********************
     ********************/

    case 'produit':
        // Appel de la méthode pour afficher les produits
        $controller = new ProduitController();
        $controller->liste();
        break;

    case 'detail_produit' :
        // Appel de la méthode pour afficher les détails du produit
        $controller = new ProduitController();
        $controller->voirDetail($Id_produit);
        break;

    case 'add_produit':
        // Appel de la méthode pour ajouter un produit
        $controller = new ProduitController();
        $controller->add();
        break;

    case 'delete_produit':
        // Appel de la méthode pour supprimer le produit
        $controller = new ProduitController();
        $controller->delete();
        break;

    case 'update_produit' :
        // Appel de la méthode pour modifier les détails du produit
        $controller = new ProduitController();
        $controller->modifier($Id_produit);
        break;

    /*********************
     *********************
     *
     * Pour les clients
     *
     *********************
     ********************/

    case 'client':
        // Appel de la méthode pour afficher les clients
        $controller = new ClientController();
        $controller->liste();
        break;

    case 'detail_client' :
        // Appel de la méthode pour afficher les détails du client
        $controller = new ClientController();
        $controller->voirDetail($Id_client);
        break;

    case 'add_client':
        // Appel de la méthode pour ajouter un client
        $controller = new ClientController();
        $controller->add();
        break;

    case 'delete_client':
        // Appel de la méthode pour supprimer un client
        $controller = new ClientController();
        $controller->delete();
        break;

    case 'update_client' :
        // Appel de la méthode pour modifier les détails du client
        $controller = new ClientController();
        $controller->modifier($Id_client);
        break;

    /*********************
     *********************
     *
     * Pour les commandes
     *
     *********************
     ********************/

    case 'commande':
        // Appel de la méthode pour afficher les commandes
        $controller = new CommandeController();
        $controller->liste();
        break;

    case 'detail_commande' :
        // Appel de la méthode pour afficher les détails de la commande
        $controller = new CommandeController();
        $controller->voirDetail($Id_commande);
        break;

    case 'update_commande' :
        // Appel de la méthode pour modifier les détails de la commande
        $controller = new CommandeController();
        $controller->modifier($Id_commande);
        break;

    /*********************
     *********************
     *
     * Pour la gestion des vendeurs
     *
     *********************
     ********************/

    case 'vendeur':
        // Appel de la méthode pour afficher les vendeurs
        $controller = new VendeurController();
        $controller->liste();
        break;

    case 'detail_vendeur' :
        // Appel de la méthode pour afficher les détails du vendeur
        $controller = new VendeurController();
        $controller->voirDetail($Id_vendeur);
        break;

    case 'add_vendeur':
        // Appel de la méthode pour ajouter un vendeur
        $controller = new VendeurController();
        $controller->add();
        break;

    case 'delete_vendeur':
        // Appel de la méthode pour supprimer un vendeur
        $controller = new VendeurController();
        $controller->delete();
        break;

    case 'update_vendeur' :
        // Appel de la méthode pour modifier les détails du vendeur
        $controller = new VendeurController();
        $controller->modifier($Id_vendeur);
        break;

    /*********************
     *********************
     *
     * Pour le profil du vendeur connecté
     *
     *********************
     ********************/

    case 'profil':
        // Appel de la méthode pour afficher les détails du profil
        $controller = new ProfilController();
        $controller->voirDetail($_SESSION['utilisateur']['Id_vendeur']);
        break;

    case 'update_profil' :
        // Appel de la méthode pour modifier les détails du profil
        $controller = new ProfilController();
        $controller->modifierInfo($_SESSION['utilisateur']['Id_vendeur']);
        break;

    case 'update_password' :
        // Appel de la méthode pour modifier le mot de passe du vendeur connecté
        $controller = new ProfilController();
        $controller->modifierMdp($_SESSION['utilisateur']['Id_vendeur']);
        break;

    case 'panier':
        // Appel de la méthode pour afficher tous les produits
        $controller = new PanierController();
        $controller->liste();
        break;

    /// ///////
    /// //////
    /// //////
    /// site pouvant m'aider pour stocker le panier en session :

    /// https://cours.davidannebicque.fr/m2202/seance-4
    /// https://laconsole.dev/formations/php/sessions




    default:
        echo "Cette page n'existe pas";
        break;
}