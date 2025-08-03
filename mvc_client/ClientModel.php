<?php

namespace mvc_client;
use Database;
use PDO;
use PDOException;

require_once 'models/Database.php';

class ClientModel {

    // propriétés privées (encapsulation)
    private $pdo;
    private $Id_client; // id unique de la BDD
    private $Nom;
    private $Prenom;
    private $Telephone;
    private $Adresse;


    // Constructeur : initialisation du produit
    // public function => pour que la fonction soit accessible partout
    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    // Getter pour l'id
    public function getId() {
        return $this->Id_client;
    }

    // Getter et setter pour le nom du client
    public function getNom() {
        return $this->Nom;
    }
    public function setNom($Nom) {
        $this->Nom = $Nom;
    }

    // Getter et setter pour le prénom du client
    public function getPrenom() {
        return $this->Prenom;
    }
    public function setPrenom($Prenom) {
        $this->Prenom = $Prenom;
    }

    // Getter et setter pour le n° de téléphone du client
    public function getTelephone() {
        return $this->Telephone;
    }
    public function setTelephone($Telephone) {
        $this->Telephone = $Telephone;
    }

    // Getter et setter pour l'adresse du client
    public function getAdresse() {
        return $this->Adresse;
    }
    public function setAdresse($Adresse) {
        $this->Adresse = $Adresse;
    }

    //Méthode pour charger les produits provenant de la BDD

    /**
     * Charger un produit depuis la BDD via son ID
     *
     * @param int Id_produit id de du produit
     * @return array|null retourne l'objet produit (ou rien si non trouvé)
     */

    public static function lister() {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();

        // Récupération des infos dans la BDD
        $stmt = $db->prepare("SELECT * FROM client");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ajouter($Nom, $Prenom, $Telephone, $Adresse) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();
        try {
            // Préparation de la requête
            $stmt = $db->prepare("INSERT INTO client (Nom_client, Prenom_client, Telephone_client, Adresse_client) VALUES (?, ?, ?, ?)");

            // Exécution
            $stmt->execute([$Nom, $Prenom, $Telephone, $Adresse]);
            return null; // Pas d'erreur
        } catch (PDOException $e) {
            return $e->getMessage(); // Retourne le message d'erreur
        }
    }

    // Modification d'un produit dans la BDD
    public static function modifier($Nom, $Prenom, $Telephone, $Adresse, $Id_client) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();
        // Màj
        $stmt = $db->prepare("UPDATE client SET Nom_client=?, Prenom_client=?, Telephone_client=?, Adresse_client=? WHERE Id_client=?");
        $stmt->execute([$Nom, $Prenom, $Telephone, $Adresse, $Id_client]);
    }

    public static function delete($Id_client) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM client WHERE Id_client = ?");
        return $stmt->execute([$Id_client]);
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

    public static function loadById(int $Id_client) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();

        // Récupération des infos dans la BDD
        $stmt = $db->prepare("SELECT * FROM client WHERE Id_client = ?");
        $stmt->execute([$Id_client]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $client = new ClientModel();
            $client->Id_client = $data['Id_client'];
            $client->setNom($data['Nom_client']);
            $client->setPrenom($data['Prenom_client']);
            $client->setTelephone($data['Telephone_client']);
            $client->setAdresse($data['Adresse_client']);
            return $client;
        }

        // sinon on retourne null
        return null;
    }
}