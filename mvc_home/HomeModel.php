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
    private $Id_produit; // id unique de la BDD
    private $Nom_produit;
    private $Prix_TTC;
    private $Stock;
    private $Description;
    private $Prix_HT;
    private $Tva_categorie;
    private $Nom_categorie;
    private $Type_conditionnement;


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
    public function getId() {
        return $this->Id_produit;
    }

    /**
     * Récupère le nom du produit
     *
     * @return string Nom du produit
     */
    public function getNom_produit() {
        return $this->Nom_produit;
    }

    /**
     * Définit le nom du produit
     *
     * @param string $Nom_produit Nom du produit
     *
     * @return void
     */
    public function setNom_produit($Nom_produit) {
        $this->Nom_produit = $Nom_produit;
    }

    /**
     * Récupère le prix TTC du produit
     *
     * @return float Prix TTC du produit
     */
    public function getPrix_TTC() {
        return $this->Prix_TTC;
    }

    /**
     * Définit le prix TTC du produit
     *
     * @param float $Prix_TTC Prix TTC du produit
     *
     * @return void
     */
    public function setPrix_TTC($Prix_TTC) {
        $this->Prix_TTC = $Prix_TTC;
    }

    /**
     * Récupère la quantité en stock du produit
     *
     * @return int Quantité en stock
     */
    public function getStock() {
        return $this->Stock;
    }

    /**
     * Définit la quantité en stock du produit
     *
     * @param int $Stock Quantité en stock
     *
     * @return void
     */
    public function setStock($Stock) {
        $this->Stock = $Stock;
    }

    /**
     * Récupère la description du produit
     *
     * @return string Description du produit
     */
    public function getDescription() {
        return $this->Description;
    }

    /**
     * Définit la description du produit
     *
     * @param string $Description Description du produit
     *
     * @return void
     */
    public function setDescription($Description) {
        $this->Description = $Description;
    }

    /**
     * Récupère le prix HT du produit
     *
     * @return float Prix HT du produit
     */
    public function getPrix_HT() {
        return $this->Prix_HT;
    }

    /**
     * Définit le prix HT du produit
     *
     * @param float $Prix_HT Prix HT du produit
     *
     * @return float Prix HT défini
     */
    public function setPrix_HT($Prix_HT) {
        return $this->Prix_HT = $Prix_HT;
    }

    /**
     * Récupère le taux de TVA de la catégorie
     *
     * @return float Taux de TVA de la catégorie
     */
    public function getTva_categorie() {
        return $this->Tva_categorie;
    }

    /**
     * Définit le taux de TVA de la catégorie
     *
     * @param float $Tva_categorie Taux de TVA de la catégorie
     *
     * @return float Taux de TVA défini
     */
    public function setTva_categorie($Tva_categorie) {
        return $this->Tva_categorie = $Tva_categorie;
    }

    /**
     * Récupère le nom de la catégorie du produit
     *
     * @return string Nom de la catégorie
     */
    public function getNom_categorie() {
        return $this->Nom_categorie;
    }

    /**
     * Définit le nom de la catégorie du produit
     *
     * @param string $Nom_categorie Nom de la catégorie
     *
     * @return string Nom de la catégorie défini
     */
    public function setNom_categorie($Nom_categorie) {
        return $this->Nom_categorie = $Nom_categorie;
    }

    /**
     * Récupère le type de conditionnement du produit
     *
     * @return string Type de conditionnement (ex: sachet, boîte, vrac)
     */
    public function getType_conditionnement() {
        return $this->Type_conditionnement;
    }

    /**
     * Définit le type de conditionnement du produit
     *
     * @param string $Type_conditionnement Type de conditionnement
     *
     * @return void
     */
    public function setType_conditionnement($Type_conditionnement) {
        return $this->Type_conditionnement = $Type_conditionnement;
    }


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