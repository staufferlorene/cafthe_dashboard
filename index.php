<?php

// 2 lignes ci-dessous permettent d'afficher les erreurs PHP
error_reporting(E_ALL);
ini_set("display_errors", 1);

/**
 *Front controller
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

// Récupération des paramètres de l'action via l'URL (ex : index.php?action=produit)
$action = isset($_GET['action']) ? $_GET['action'] : 'produit';

// Récupération de l'id du produit
$Id_produit = isset($_GET['Id_produit']) ? intval($_GET['Id_produit']) : 0;

// Récupération de l'id du client
$Id_client = isset($_GET['Id_client']) ? intval($_GET['Id_client']) : 0;



switch ($action) {

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

    case 'detail_produit' :
        // Appel de la méthode pour afficher les détails du produit
        $controller = new ProduitController();
        $controller->voirDetail($Id_produit);
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

    case 'detail_client' :
        // Appel de la méthode pour afficher les détails du client
        $controller = new ClientController();
        $controller->voirDetail($Id_client);
        break;

    default:
        echo "Cette page n'existe pas";
        break;
}