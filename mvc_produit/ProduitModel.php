<?php

namespace mvc_produit;
use Database;
use PDO;
use PDOException;

require_once 'models/Database.php';

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


    // Constructeur : initialisation du produit
    // public function => pour que la fonction soit accessible partout
    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    // Getter pour l'id
    public function getId() {
        return $this->Id_produit;
    }

    // Getter pour le Nom_produit
    public function getNom_produit() {
        return $this->Nom_produit;
    }

    // Getter pour le Prix_TTC
    public function getPrix_TTC() {
        return $this->Prix_TTC;
    }

    // Getter pour le Stock
    public function getStock() {
        return $this->Stock;
    }

    // Getter pour la Description
    public function getDescription() {
        return $this->Description;
    }

    // Getter pour le Prix_HT
    public function getPrix_HT() {
        return $this->Prix_HT;
    }

    // Getter pour la TVA
    public function getTva_categorie() {
        return $this->Tva_categorie;
    }

    // Getter pour la Catégorie
    public function getNom_categorie() {
        return $this->Nom_categorie;
    }

    // Getter pour le Type de conditionnement
    public function getType_conditionnement() {
        return $this->Type_conditionnement;
    }

    public function setNom_produit($Nom_produit) {
        $this->Nom_produit = $Nom_produit;
    }

    public function setPrix_TTC($Prix_TTC) {
        $this->Prix_TTC = $Prix_TTC;
    }

    public function setStock($Stock) {
        $this->Stock = $Stock;
    }

    public function setDescription($Description) {
        $this->Description = $Description;
    }

    public function setPrix_HT($Prix_HT) {
        return $this->Prix_HT = $Prix_HT;
    }

    public function setTva_categorie($Tva_categorie) {
        return $this->Tva_categorie = $Tva_categorie;
    }

    public function setNom_categorie($Nom_categorie) {
        return $this->Nom_categorie = $Nom_categorie;
    }

    public function setType_conditionnement($Type_conditionnement) {
        return $this->Type_conditionnement = $Type_conditionnement;
    }

    //Méthode pour charger les produits provenant de la BDD

    /**
     * Charger un produit depuis la BDD via son ID
     *
     * @param int Id_produit id de du produit
     * @return array|null retourne l'objet produit (ou rien si non trouvé)
     */

    public static function lister() {
        /* public static function lister(int $Id_produit) {*/
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();

        // Récupération des infos dans la BDD
        $stmt = $db->prepare("SELECT * FROM produit JOIN categorie ON categorie.Id_categorie = produit.Id_categorie");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

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

    // Modification d'un produit dans la BDD
    public static function modifier($Nom_produit, $Description, $Prix_TTC, $Prix_HT, $Stock, $Type_conditionnement, $categories , $Id_produit) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();
        // Màj
        $stmt = $db->prepare("UPDATE produit SET Nom_produit=?, Description=?, Prix_TTC=?, Prix_HT=?, Stock=?, Type_conditionnement=?, Id_categorie=? WHERE Id_produit=?");
        $stmt->execute([$Nom_produit, $Description, $Prix_TTC, $Prix_HT, $Stock, $Type_conditionnement, $categories, $Id_produit]);
    }

    public static function delete($Id_produit) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM produit WHERE Id_produit = ?");
        return $stmt->execute([$Id_produit]);
    }

/*    public function save() {
        $db = Database::getInstance()->getConnection();

        if ($this->Id_produit === null) {
            //insertion
            $stmt = $db->prepare("INSERT INTO produit(Nom_produit, Prix_TTC, Stock) VALUE(?,?,?)");
            $stmt->execute([$this->Nom_produit, $this->Prix_TTC, $this->Stock]);

            //Recuperation de l'id généré par MySQL
            $this->Id_produit = $db->lastInsertId();
        } else {
            //Mise à jour si la voiture existe déjà
            $stmt = $db->prepare("UPDATE produit SET Nom_produit =?, Prix_TTC =?, Stock=? WHERE Id_produit = ?");
            $stmt->execute([$this->Nom_produit, $this->Prix_TTC, $this->Stock, $this->Id_produit]);
        }
    }*/

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
            return $produit;
        }

        // sinon on retourne null
        return null;
    }

    public static function categories() {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();

        // Récupération des infos dans la BDD
        $stmt = $db->prepare("SELECT * FROM categorie");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}