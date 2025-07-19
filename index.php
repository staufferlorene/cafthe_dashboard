<?php
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
require_once 'controllers/ProduitsControllers.php';
require_once 'controllers/UtilisateursControllers.php';

// Récupération des paramètres de l'action via l'URL (ex : index.php?action=produits)
$action = isset($_GET['action']) ? $_GET['action'] : 'produits';

// Même chose avec l'id
$id_produits = isset($_GET['id_produits']) ? intval($_GET['id_produits']) : 0;


switch ($action) {
    case 'produits':
        // Appel de la méthode pour afficher les produits
        $controller = new ProduitsController();
        $controller->liste();
        break;

    case 'add':
        // Appel de la méthode pour ajouter un produit
        $controller = new ProduitsController();
        $controller->add();
        break;

    case 'delete_produits':
        // Appel de la méthode pour supprimer les produits
        $controller = new ProduitsController();
        $controller->delete();
        break;

    case 'update' :
        // Appel de la méthode pour modifier les détails du produit
        $controller = new ProduitsController();
        $controller->modifier($id_produits);
        break;

    default:
        echo "Cette page n'existe pas";
        break;
}