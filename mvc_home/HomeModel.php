<?php

namespace mvc_home;
use Database;
use PDO;
use PDOException;

require_once 'config/Database.php';

/**
 * Modèle pour la gestion des statistiques et indicateurs de performance
 *
 * Cette classe gère les opérations en base de données liées aux statistiques :
 * - Produits les plus vendus
 * - Ventes par catégorie
 * - Évolution des ventes par mois
 * - Chiffre d'affaires par vendeur
 */

class HomeModel {

    // propriétés privées (encapsulation)
    private $pdo;

    /**
     * Constructeur : initialise la connexion à la base de données
     */
    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    /**
     * Récupère l'identifiant du produit
     *
     * @return int Identifiant du produit
     */

    /*********************
     *********************
     *
     * METHODES
     *
     *********************
     ********************/

    /**
     * Récupère les 6 produits les plus vendus
     *
     * Retourne les produits classés par quantité totale vendue décroissante,
     * limités aux 6 premiers résultats
     *
     * @return array Liste des produits avec leur nom et quantité totale vendue
     */
    public static function listerProduitsVendus() {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();

        // Récupération des infos dans la BDD
        $stmt = $db->prepare("
            SELECT p.Nom_produit, SUM(l.Quantite_produit_ligne_commande) AS qte_totale_vendu
            FROM ligne_commande AS l
            JOIN produit AS p ON p.Id_produit = l.Id_produit
            GROUP BY l.Id_produit
            ORDER BY qte_totale_vendu DESC
            LIMIT 6
        ");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Calcule le montant total des ventes par catégorie de produits
     *
     * @return array Liste des catégories avec leur montant total de ventes (quantité × prix unitaire)
     */
    public static function listerVentesParCategories() {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();

        // Récupération des infos dans la BDD
        $stmt = $db->prepare("
            SELECT c.Nom_categorie, SUM(l.Quantite_produit_ligne_commande * l.Prix_unitaire_ligne_commande) AS montant_total
            FROM ligne_commande AS l
            JOIN produit AS p ON p.Id_produit = l.Id_produit
            JOIN categorie AS c ON c.Id_categorie = p.Id_categorie
            GROUP BY c.Id_categorie
        ");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère l'évolution des ventes mensuelles
     *
     * Retourne le chiffre d'affaires TTC par mois au format YYYY-MM,
     * classé chronologiquement
     *
     * @return array Liste des mois avec leur montant total TTC
     */
    public static function listerVentesParMois() {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();

        // Récupération des infos dans la BDD
        $stmt = $db->prepare("
            SELECT DATE_FORMAT(Date_commande, \"%Y-%m\") AS mois, SUM(Montant_commande_TTC) AS total_mois
            FROM commande
            GROUP BY mois
            ORDER BY mois
        ");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Calcule le chiffre d'affaires total par vendeur
     *
     * Retourne les vendeurs classés par CA décroissant avec leur nom, prénom
     * et montant total TTC des commandes
     *
     * @return array Liste des vendeurs avec leur chiffre d'affaires total
     */
    public static function listerCAParVendeur() {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();

        // Récupération des infos dans la BDD
        $stmt = $db->prepare("
            SELECT v.Nom_vendeur, v.Prenom_vendeur, SUM(c.Montant_commande_TTC) AS ca_total
            FROM commande AS c
            JOIN vendeur AS v ON v.Id_vendeur = c.Id_vendeur
            GROUP BY v.Id_vendeur
            ORDER BY ca_total DESC
        ");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}