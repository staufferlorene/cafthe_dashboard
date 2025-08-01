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
require_once 'controllers/UtilisateursControllers.php';

// Récupération des paramètres de l'action via l'URL (ex : index.php?action=produit)
$action = isset($_GET['action']) ? $_GET['action'] : 'produit';

// Récupération de l'id du produit
$Id_produit = isset($_GET['Id_produit']) ? intval($_GET['Id_produit']) : 0;


switch ($action) {
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
        // Appel de la méthode pour supprimer les produits
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

    default:
        echo "Cette page n'existe pas";
        break;
}