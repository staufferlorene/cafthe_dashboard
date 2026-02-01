<?php

namespace mvc_produit;
use Database;
use PDO;
use PDOException;

require_once 'config/Database.php';

/**
 * Modèle pour la gestion des produits
 *
 * Cette classe gère les opérations en base de données liées aux produits :
 * - CRUD (Create, Read, Update, Delete)
 * - Récupération des catégories et conditionnements
 * - Gestion des alertes de stock
 * - Vérification des liens avec les commandes
 */

class ProduitModel {

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
    private $Id_categorie;


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

    /**
     * Récupère l'identifiant de la catégorie
     *
     * @return int Identifiant de la catégorie
     */
    public function getId_categorie() {
        return $this->Id_categorie;
    }

    /**
     * Définit l'identifiant de la catégorie
     *
     * @param int $Id_categorie Identifiant de la catégorie
     *
     * @return void
     */
    public function setId_categorie($Id_categorie) {
        $this->Id_categorie = $Id_categorie;
    }


    /*********************
     *********************
     *
     * METHODES
     *
     *********************
     ********************/

    /**
     * Liste tous les produits avec leur catégorie associée
     *
     * @return array Liste des produits et leurs catégories
     */

    public static function lister() {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();

        // Récupération des infos dans la BDD
        $stmt = $db->prepare("SELECT * FROM produit JOIN categorie ON categorie.Id_categorie = produit.Id_categorie");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ajoute un produit dans la base de données
     *
     * @param string $Nom_produit Nom du produit
     * @param string $Description Description du produit
     * @param float  $Prix_TTC Prix TTC
     * @param float  $Prix_HT Prix HT
     * @param int    $Stock Quantité en stock
     * @param string $Type_conditionnement Type de conditionnement
     * @param int    $categories Nom de la catégorie
     *
     * @return string|null Retourne null si succès, ou un message d'erreur en cas d’échec
     */

    public static function ajouter($Nom_produit, $Description, $Prix_TTC, $Prix_HT, $Stock, $Type_conditionnement, $categories) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();
        try {
            // Préparation de la requête
            $stmt = $db->prepare("INSERT INTO produit (Nom_produit, Description, Prix_TTC, Prix_HT, Stock, Type_conditionnement, Id_categorie) VALUES (?, ?, ?, ?, ?, ?, ?)");

            // Exécution
            $stmt->execute([$Nom_produit, $Description, $Prix_TTC, $Prix_HT, $Stock, $Type_conditionnement, $categories]);
            return null; // Pas d'erreur
        } catch (PDOException $e) {
            return $e->getMessage(); // Retourne le message d'erreur
        }
    }

    /**
     * Met à jour un produit existant dans la base de données
     *
     * @param string $Nom_produit Nom du produit
     * @param string $Description Description du produit
     * @param float  $Prix_TTC Prix TTC
     * @param float  $Prix_HT Prix HT
     * @param int    $Stock Quantité en stock
     * @param string $Type_conditionnement Type de conditionnement
     * @param int    $categories Nom de la catégorie
     * @param int    $Id_produit Identifiant du produit à modifier
     *
     * @return string|null Retourne null si succès, ou un message d'erreur en cas d'échec
     */

    public static function modifier($Nom_produit, $Description, $Prix_TTC, $Prix_HT, $Stock, $Type_conditionnement, $categories , $Id_produit) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();
        try {
            // Màj
            $stmt = $db->prepare("UPDATE produit SET Nom_produit=?, Description=?, Prix_TTC=?, Prix_HT=?, Stock=?, Type_conditionnement=?, Id_categorie=? WHERE Id_produit=?");
            $stmt->execute([$Nom_produit, $Description, $Prix_TTC, $Prix_HT, $Stock, $Type_conditionnement, $categories, $Id_produit]);
            return null; // Pas d'erreur
        } catch (PDOException $e) {
            return $e->getMessage(); // Retourne le message d'erreur
        }
    }

    /**
     * Supprime un produit de la base de données
     *
     * @param int $Id_produit Identifiant du produit à supprimer
     *
     * @return bool Retourne true si la fonction de suppression a réussi, false sinon
     */

    public static function delete($Id_produit) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM produit WHERE Id_produit = ?");
        return $stmt->execute([$Id_produit]);
    }

    /**
     * Charge un produit par son identifiant
     *
     * @param int $Id_produit Identifiant du produit
     *
     * @return ProduitModel|null Retourne un objet ProduitModel ou null si non trouvé
     */

    public static function loadById(int $Id_produit) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();

        // Récupération des infos dans la BDD
        $stmt = $db->prepare("SELECT * FROM produit JOIN categorie ON categorie.Id_categorie = produit.Id_categorie WHERE Id_produit = ?");
        $stmt->execute([$Id_produit]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $produit = new ProduitModel();
            $produit->Id_produit = $data['Id_produit'];
            $produit->setNom_produit($data['Nom_produit']);
            $produit->setDescription($data['Description']);
            $produit->setPrix_TTC($data['Prix_TTC']);
            $produit->setPrix_HT($data['Prix_HT']);
            $produit->setTva_categorie($data['Tva_categorie']);
            $produit->setStock($data['Stock']);
            $produit->setNom_categorie($data['Nom_categorie']);
            $produit->setType_conditionnement($data['Type_conditionnement']);
            $produit->setId_categorie($data['Id_categorie']);
            return $produit;
        }

        // sinon on retourne null
        return null;
    }

    /**
     * Récupère toutes les catégories de produits
     *
     * @return array Liste des catégories
     */

    public static function categories() {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();

        // Récupération des infos dans la BDD
        $stmt = $db->prepare("SELECT * FROM categorie");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Vérifie si le produit a au moins une ligne de commande qui lui est rattaché
     *
     * @param int $Id_produit Identifiant du produit
     *
     * @return bool Retourne true si le produit a des commandes, false sinon
     */

    public static function haveOrder($Id_produit) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("SELECT COUNT(*) FROM ligne_commande WHERE Id_produit = ?");
        $stmt->execute([$Id_produit]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Récupère tous les types de conditionnement distincts
     *
     * @return array Liste des conditionnements
     */
    public static function conditionnements() {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("SELECT DISTINCT Type_conditionnement FROM produit");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les produits dont le stock est inférieur ou égal à 5
     *
     * @return array Liste des produits en alerte de stock (nom et quantité)
     */
    public static function getStockAlert() {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("SELECT Nom_produit, Stock FROM produit WHERE Stock <= 5 ORDER BY Stock ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}