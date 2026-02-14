<?php

// 2 lignes ci-dessous permettent d'afficher les erreurs PHP
global $smarty;
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
require_once 'mvc_home/HomeController.php';
require_once 'template/TopBarModel.php';

// Vérification si l'utilisateur est connecté
if (isset($_SESSION['utilisateur'])) {
    // Passage des infos de l'utilisateur à smarty
    $smarty->assign('utilisateur', $_SESSION['utilisateur']);

    // Récupération des produits dont stock <= 5 et passage des infos du produit à smarty
    $alertesStock = template\TopBarModel::getStockAlert();
    $smarty->assign('alertesStock', $alertesStock);
    $smarty->assign('nbAlertesStock', count($alertesStock));

    // Récupération des paramètres de l'action via l'URL, si pas d'action affichage de la page d'accueil
    $action = isset($_GET['action']) ? $_GET['action'] : 'home';
} else {
    // Si utilisateur non connecté affichage de la page de connexion
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
        $controllerLogin = new LoginController();
        $controllerLogin->login();
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
        $controllerLogout = new LoginController();
        $controllerLogout->logout();
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
        $controllerProduit = new ProduitController();
        $controllerProduit->liste();
        break;

    case 'detail_produit' :
        // Appel de la méthode pour afficher les détails du produit
        $controllerProduit = new ProduitController();
        $controllerProduit->voirDetail($Id_produit);
        break;

    case 'add_produit':
        // Appel de la méthode pour ajouter un produit
        $controllerProduit = new ProduitController();
        $controllerProduit->add();
        break;

    case 'delete_produit':
        // Appel de la méthode pour supprimer le produit
        $controllerProduit = new ProduitController();
        $controllerProduit->delete();
        break;

    case 'update_produit' :
        // Appel de la méthode pour modifier les détails du produit
        $controllerProduit = new ProduitController();
        $controllerProduit->modifier($Id_produit);
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
        $controllerClient = new ClientController();
        $controllerClient->liste();
        break;

    case 'detail_client' :
        // Appel de la méthode pour afficher les détails du client
        $controllerClient = new ClientController();
        $controllerClient->voirDetail($Id_client);
        break;

    case 'add_client':
        // Appel de la méthode pour ajouter un client
        $controllerClient = new ClientController();
        $controllerClient->add();
        break;

    case 'delete_client':
        // Appel de la méthode pour supprimer un client
        $controllerClient = new ClientController();
        $controllerClient->delete();
        break;

    case 'update_client' :
        // Appel de la méthode pour modifier les détails du client
        $controllerClient = new ClientController();
        $controllerClient->modifier($Id_client);
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
        $controllerCommande = new CommandeController();
        $controllerCommande->liste();
        break;

    case 'detail_commande' :
        // Appel de la méthode pour afficher les détails de la commande
        $controllerCommande = new CommandeController();
        $controllerCommande->voirDetail($Id_commande);
        break;

    case 'update_commande' :
        // Appel de la méthode pour modifier les détails de la commande
        $controllerCommande = new CommandeController();
        $controllerCommande->modifier($Id_commande);
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
        $controllerVendeur = new VendeurController();
        $controllerVendeur->liste();
        break;

    case 'detail_vendeur' :
        // Appel de la méthode pour afficher les détails du vendeur
        $controllerVendeur = new VendeurController();
        $controllerVendeur->voirDetail($Id_vendeur);
        break;

    case 'add_vendeur':
        // Appel de la méthode pour ajouter un vendeur
        $controllerVendeur = new VendeurController();
        $controllerVendeur->add();
        break;

    case 'delete_vendeur':
        // Appel de la méthode pour supprimer un vendeur
        $controllerVendeur = new VendeurController();
        $controllerVendeur->delete();
        break;

    case 'update_vendeur' :
        // Appel de la méthode pour modifier les détails du vendeur
        $controllerVendeur = new VendeurController();
        $controllerVendeur->modifier($Id_vendeur);
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
        $controllerProfil = new ProfilController();
        $controllerProfil->voirDetail($_SESSION['utilisateur']['Id_vendeur']);
        break;

    case 'update_profil' :
        // Appel de la méthode pour modifier les détails du profil
        $controllerProfil = new ProfilController();
        $controllerProfil->modifierInfo($_SESSION['utilisateur']['Id_vendeur']);
        break;

    case 'update_password' :
        // Appel de la méthode pour modifier le mot de passe du vendeur connecté
        $controllerProfil = new ProfilController();
        $controllerProfil->modifierMdp($_SESSION['utilisateur']['Id_vendeur']);
        break;

    /*********************
     *********************
     *
     * Pour la gestion du panier
     *
     *********************
     ********************/

    case 'panier':
        // Appel de la méthode pour afficher tous les produits
        $controllerPanier = new PanierController();
        $controllerPanier->liste();
        break;

    case 'add_panier' :
        // Appel de la méthode pour ajouter au panier
        $controllerPanier = new PanierController();
        $controllerPanier->ajoutPanier();
        break;

    case 'view_panier' :
        // Appel de la méthode pour afficher le panier
        $controllerPanier = new PanierController();
        $controllerPanier->voirPanier();
        break;

    case 'delete_panier':
        // Appel de la méthode pour supprimer un produit du panier
        $controllerPanier = new PanierController();
        $controllerPanier->delete();
        break;

    case 'update_panier' :
        // Appel de la méthode pour modifier le panier
        $controllerPanier = new PanierController();
        $controllerPanier->modifierPanier();
        break;

    case 'client_panier':
        // Appel de la méthode pour rattacher un client au panier
        $controllerPanier = new PanierController();
        $controllerPanier->listeChoixClient();
        break;

    case 'recap_panier':
        // Appel de la méthode pour récapituler le panier avant encaissement
        $controllerPanier = new PanierController();
        $controllerPanier->checkPanier();
        break;

    case 'paiement_panier':
        // Appel de la méthode pour payer le panier
        $controllerPanier = new PanierController();
        $controllerPanier->paiementPanier();
        break;

    /*********************
     *********************
     *
     * Pour les stats
     *
     *********************
     ********************/

    case 'home':
        // Appel de la méthode pour afficher toutes les stats
        $controllerHome = new HomeController();
        $controllerHome->afficherStat();
        break;

    default:
        echo "Cette page n'existe pas";
        break;
}