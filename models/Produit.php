<?php

require_once 'Database.php';

class Produit {

    // propriétés privées (encapsulation)
    private $pdo;
    private $Id_produit; // id unique de la BDD
    private $Nom_produit;
    private $Prix_TTC;
    private $Stock;


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

    // Get des détails du produit
    public function getDetails() {
        return "Produit : " .$this->Id_produit . $this->Nom_produit . " " . $this->Prix_TTC . " " . $this->Stock;
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

    public function showDetails() {
        echo "Produit : " .$this->Id_produit . $this->Nom_produit . " " . $this->Prix_TTC . " " . $this->Stock;
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
        $stmt = $db->prepare("SELECT * FROM produit");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ajouter($Nom_produit, $Prix_TTC, $Stock) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();
        try {
            // Préparation de la requête
            $stmt = $db->prepare("INSERT INTO produit (Nom_produit, Prix_TTC, Stock) VALUES (?, ?, ?)");

            // Exécution
            $stmt->execute([$Nom_produit, $Prix_TTC, $Stock]);
            return null; // Pas d'erreur
        } catch (PDOException $e) {
            return $e->getMessage(); // Retourne le message d'erreur
        }
    }


    // Modification d'un produit dans la BDD
    public static function modifier ($Nom_produit, $Prix_TTC, $Stock, $Id_produit) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();
        // Màj
        $stmt = $db->prepare("UPDATE produit SET Nom_produit=?, Prix_TTC=?, Stock=? WHERE Id_produit=?");
        $stmt->execute([$Nom_produit, $Prix_TTC, $Stock, $Id_produit]);
    }

    public static function delete($Id_produit) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM produit WHERE Id_produit = ?");
        return $stmt->execute([$Id_produit]);
    }

    public function save()
    {
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
    }

    public static function loadById(int $Id_produit) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();

        // Récupération des infos dans la BDD
        $stmt = $db->prepare("SELECT * FROM produit WHERE Id_produit = ?");
        $stmt->execute([$Id_produit]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $produit = new Produit();
            $produit->Id_produit = $data['Id_produit'];
            $produit->setNom_produit($data['Nom_produit']);
            $produit->setPrix_TTC($data['Prix_TTC']);
            $produit->setStock($data['Stock']);
            return $produit;
        }

        // sinon on retourne null
        return null;
    }

}