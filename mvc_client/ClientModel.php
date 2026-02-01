<?php

namespace mvc_client;
use Database;
use PDO;
use PDOException;

require_once 'config/Database.php';

/**
 * Modèle pour la gestion des clients
 *
 * Cette classe gère les opérations en base de données liées aux clients :
 * - CRUD (Create, Read, Update, Delete)
 * - Vérification des liens avec les commandes
 */

class ClientModel {

    // propriétés privées (encapsulation)
    private $pdo;
    private $Id_client; // id unique de la BDD
    private $Nom_client;
    private $Prenom_client;
    private $Telephone_client;
    private $Adresse_client;
    private $Mail_client;

    /**
     * Constructeur : initialise la connexion à la base de données
     */
    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    /**
     * Récupère l'identifiant du client
     *
     * @return int Identifiant du client
     */
    public function getId() {
        return $this->Id_client;
    }

    /**
     * Récupère le nom du client
     *
     * @return string Nom du client
     */
    public function getNom_client() {
        return $this->Nom_client;
    }

    /**
     * Définit le nom du client
     *
     * @param string $Nom_client Nom du client
     *
     * @return void
     */
    public function setNom_client($Nom_client) {
        $this->Nom_client = $Nom_client;
    }

    /**
     * Récupère le prénom du client
     *
     * @return string Prénom du client
     */
    public function getPrenom_client() {
        return $this->Prenom_client;
    }

    /**
     * Définit le prénom du client
     *
     * @param string $Prenom_client Prénom du client
     *
     * @return void
     */
    public function setPrenom_client($Prenom_client) {
        $this->Prenom_client = $Prenom_client;
    }

    /**
     * Récupère le numéro de téléphone du client
     *
     * @return string Numéro de téléphone du client
     */
    public function getTelephone_client() {
        return $this->Telephone_client;
    }

    /**
     * Définit le numéro de téléphone du client
     *
     * @param string $Telephone_client Numéro de téléphone du client
     *
     * @return void
     */
    public function setTelephone_client($Telephone_client) {
        $this->Telephone_client = $Telephone_client;
    }

    /**
     * Récupère l'adresse postale du client
     *
     * @return string Adresse postale du client
     */
    public function getAdresse_client() {
        return $this->Adresse_client;
    }

    /**
     * Définit l'adresse postale du client
     *
     * @param string $Adresse_client Adresse postale du client
     *
     * @return void
     */
    public function setAdresse_client($Adresse_client) {
        $this->Adresse_client = $Adresse_client;
    }

    /**
     * Récupère l'adresse email du client
     *
     * @return string Adresse email du client
     */
    public function getMail_client() {
        return $this->Mail_client;
    }

    /**
     * Définit l'adresse email du client
     *
     * @param string $Mail_client Adresse email du client
     *
     * @return void
     */
    public function setMail_client($Mail_client) {
        $this->Mail_client = $Mail_client;
    }


    /*********************
     *********************
     *
     * METHODES
     *
     *********************
     ********************/


    /**
     * Liste tous les clients
     *
     * @return array Liste des clients
     */

    public static function lister() {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();

        // Récupération des infos dans la BDD
        $stmt = $db->prepare("SELECT * FROM client");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ajoute un client dans la base de données
     *
     * @param string $Nom_client Nom du client
     * @param string $Prenom_client Prénom du client
     * @param string $Adresse_client Adresse postale du client
     * @param string $Telephone_client Numéro de téléphone du client
     * @param string $Mail_client Adresse email du client
     *
     * @return string|null Retourne null si succès, ou un message d'erreur en cas d'échec
     */
    public static function ajouter($Nom_client, $Prenom_client, $Adresse_client, $Telephone_client, $Mail_client) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();
        try {
            // Préparation de la requête
            $stmt = $db->prepare("INSERT INTO client (Nom_client, Prenom_client, Adresse_client, Telephone_client, Mail_client) VALUES (?, ?, ?, ?, ?)");

            // Exécution
            $stmt->execute([$Nom_client, $Prenom_client, $Adresse_client, $Telephone_client, $Mail_client]);
            return null; // Pas d'erreur
        } catch (PDOException $e) {
            return $e->getMessage(); // Retourne le message d'erreur
        }
    }

    /**
     * Met à jour un client existant dans la base de données
     *
     * @param string $Nom_client Nom du client
     * @param string $Prenom_client Prénom du client
     * @param string $Adresse_client Adresse postale du client
     * @param string $Telephone_client Numéro de téléphone du client
     * @param string $Mail_client Adresse email du client
     * @param int $Id_client Identifiant du client à modifier
     *
     * @return string|null Retourne null si succès, ou un message d'erreur en cas d'échec
     */
    public static function modifier($Nom_client, $Prenom_client, $Adresse_client, $Telephone_client, $Mail_client, $Id_client) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();
        try {
            // Màj
            $stmt = $db->prepare("UPDATE client SET Nom_client=?, Prenom_client=?, Adresse_client=?, Telephone_client=?, Mail_client=? WHERE Id_client=?");
            $stmt->execute([$Nom_client, $Prenom_client, $Adresse_client, $Telephone_client, $Mail_client, $Id_client]);
            return null; // Pas d'erreur
        } catch (PDOException $e) {
            return $e->getMessage(); // Retourne le message d'erreur
        }
    }

    /**
     * Supprime un client de la base de données
     *
     * @param int $Id_client Identifiant du client à supprimer
     * @return bool Retourne true si la fonction de suppression a réussi, false sinon
     */

    public static function delete($Id_client) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM client WHERE Id_client = ?");
        return $stmt->execute([$Id_client]);
    }

    /**
     * Charge un client par son identifiant
     *
     * @param int $Id_client Identifiant du client
     *
     * @return ClientModel|null Retourne un objet ClientModel ou null si non trouvé
     */

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
            $client->setNom_client($data['Nom_client']);
            $client->setPrenom_client($data['Prenom_client']);
            $client->setTelephone_client($data['Telephone_client']);
            $client->setAdresse_client($data['Adresse_client']);
            $client->setMail_client($data['Mail_client']);
            return $client;
        }

        // sinon on retourne null
        return null;
    }

    /**
     * Vérifie si le client a au moins une commande qui lui est rattachée
     *
     * @param int $Id_client Identifiant du client
     *
     * @return bool Retourne true si le client a des commandes, false sinon
     */
    public static function haveOrder($Id_client) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("SELECT COUNT(*) FROM commande WHERE Id_client = ?");
        $stmt->execute([$Id_client]);
        return $stmt->fetchColumn() > 0;
    }
}