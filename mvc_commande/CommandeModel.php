<?php

namespace mvc_commande;
use Database;
use PDO;
use PDOException;

require_once 'config/Database.php';

/**
 * Modèle pour la gestion des commandes
 *
 * Cette classe gère les opérations en base de données liées aux commandes :
 * - Lecture des commandes et de leurs détails
 * - Modification du statut des commandes
 * - Récupération des informations client et produits liés
 * - Gestion des statuts de commande
 */

class CommandeModel {

    // propriétés privées (encapsulation)
    private $pdo;
    private $Id_commande; // id unique de la BDD
    private $Nom_client;
    private $Prenom_client;
    private $Adresse_livraison;
    private $Date_commande;
    private $Statut_commande;
    private $Montant_commande_TTC;
    private $Nom_produit;
    private $Quantite_produit_ligne_commande;
    private $Montant_commande_HT;
    private $Montant_TVA;


    /**
     * Constructeur : initialise la connexion à la base de données
     */
    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    /**
     * Récupère l'identifiant de la commande
     *
     * @return int Identifiant de la commande
     */
    public function getId() {
        return $this->Id_commande;
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
     * Récupère l'adresse de livraison
     *
     * @return string Adresse de livraison de la commande
     */
    public function getAdresse_livraison() {
        return $this->Adresse_livraison;
    }

    /**
     * Définit l'adresse de livraison
     *
     * @param string $Adresse_livraison Adresse de livraison
     *
     * @return void
     */
    public function setAdresse_livraison($Adresse_livraison) {
        $this->Adresse_livraison = $Adresse_livraison;
    }

    /**
     * Récupère le statut de la commande
     *
     * @return string Statut de la commande (ex: En cours, Livrée, Annulée)
     */
    public function getStatut_commande() {
        return $this->Statut_commande;
    }

    /**
     * Définit le statut de la commande
     *
     * @param string $Statut_commande Statut de la commande
     *
     * @return void
     */
    public function setStatut_commande($Statut_commande) {
        $this->Statut_commande = $Statut_commande;
    }

    /**
     * Récupère la date de la commande
     *
     * @return string Date de la commande
     */
    public function getDate_commande() {
        return $this->Date_commande;
    }

    /**
     * Définit la date de la commande
     *
     * @param string $Date_commande Date de la commande
     *
     * @return void
     */
    public function setDate_commande($Date_commande) {
        $this->Date_commande = $Date_commande;
    }

    /**
     * Récupère le montant TTC de la commande
     *
     * @return float Montant total TTC de la commande
     */
    public function getMontant_commande_TTC() {
        return $this->Montant_commande_TTC;
    }

    /**
     * Définit le montant TTC de la commande
     *
     * @param float $Montant_commande_TTC Montant TTC
     *
     * @return void
     */
    public function setMontant_commande_TTC($Montant_commande_TTC) {
        $this->Montant_commande_TTC = $Montant_commande_TTC;
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
     * Récupère la quantité de produit commandée
     *
     * @return int Quantité de produit dans la ligne de commande
     */
    public function getQuantite_produit_ligne_commande() {
        return $this->Quantite_produit_ligne_commande;
    }

    /**
     * Définit la quantité de produit commandée
     *
     * @param int $Quantite_produit_ligne_commande Quantité commandée
     *
     * @return void
     */
    public function setQuantite_produit_ligne_commande($Quantite_produit_ligne_commande) {
        $this->Quantite_produit_ligne_commande = $Quantite_produit_ligne_commande;
    }

    /**
     * Récupère le montant HT de la commande
     *
     * @return float Montant total HT de la commande
     */
    public function getMontant_commande_HT() {
        return $this->Montant_commande_HT;
    }

    /**
     * Définit le montant HT de la commande
     *
     * @param float $Montant_commande_HT Montant HT
     *
     * @return void
     */
    public function setMontant_commande_HT($Montant_commande_HT) {
        $this->Montant_commande_HT = $Montant_commande_HT;
    }

    /**
     * Récupère le montant de TVA de la commande
     *
     * @return float Montant total de la TVA
     */
    public function getMontant_TVA() {
        return $this->Montant_TVA;
    }

    /**
     * Définit le montant de TVA de la commande
     *
     * @param float $Montant_TVA Montant de la TVA
     *
     * @return void
     */
    public function setMontant_TVA($Montant_TVA) {
        $this->Montant_TVA = $Montant_TVA;
    }


    /*********************
     *********************
     *
     * METHODES
     *
     *********************
     ********************/


    /**
     * Liste toutes les commandes et leur statut associé
     *
     * @return array Liste des commandes et de leurs statuts
     */

    public static function lister() {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();

        // Récupération des infos dans la BDD
        $stmt = $db->prepare("SELECT * FROM commande JOIN client ON client.Id_client = commande.Id_client");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Met à jour le statut d'une commande existante dans la base de données
     *
     * @param string $Statut_commande Nouveau statut de la commande
     * @param int $Id_commande Identifiant de la commande à modifier
     *
     * @return string|null Retourne null si succès, ou un message d'erreur en cas d'échec
     */

    public static function modifier($Statut_commande, $Id_commande) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();
        try {
            // Màj
            $stmt = $db->prepare("UPDATE commande SET Statut_commande=? WHERE Id_commande=?");
            $stmt->execute([$Statut_commande, $Id_commande]);
            return null; // Pas d'erreur
        } catch (PDOException $e) {
            return $e->getMessage(); // Retourne le message d'erreur
        }
    }

    /**
     * Charge une commande avec les informations du client grâce à l'identifiant de commande
     *
     * Récupère les données de la commande avec les informations client associées
     *
     * @param int $Id_commande Identifiant de la commande
     *
     * @return CommandeModel|null Retourne un objet CommandeModel ou null si non trouvé
     */
    public static function loadByIdClient(int $Id_commande) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();

        // Récupération des infos dans la BDD
        $stmt = $db->prepare("SELECT * FROM produit as p JOIN ligne_commande as l ON p.Id_produit = l.Id_produit JOIN commande as c ON l.Id_commande = c.Id_commande JOIN client as cl ON c.Id_client = cl.Id_client WHERE l.Id_commande = ?");
        $stmt->execute([$Id_commande]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $commande = new CommandeModel();
            $commande->Id_commande = $data['Id_commande'];
            $commande->setNom_client($data['Nom_client']);
            $commande->setPrenom_client($data['Prenom_client']);
            $commande->setAdresse_livraison($data['Adresse_livraison']);
            $commande->setDate_commande($data['Date_commande']);
            $commande->setStatut_commande($data['Statut_commande']);
            $commande->setMontant_commande_TTC($data['Montant_commande_TTC']);

            return $commande;
        }

        // sinon on retourne null
        return null;
    }

    /**
     * Récupère toutes les lignes de commande (produits) pour une commande donnée
     *
     * Retourne un tableau contenant tous les produits de la commande avec leurs détails
     * (nom, quantité, prix, etc.)
     *
     * @param int $Id_commande Identifiant de la commande
     *
     * @return array Liste des produits de la commande
     */
    public static function loadByIdCommande(int $Id_commande) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();

        // Récupération des infos dans la BDD
        $stmt = $db->prepare("SELECT * FROM produit as p JOIN ligne_commande as l ON p.Id_produit = l.Id_produit JOIN commande as c ON l.Id_commande = c.Id_commande JOIN client as cl ON c.Id_client = cl.Id_client WHERE l.Id_commande = ?");
        $stmt->execute([$Id_commande]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère tous les statuts de commande distincts
     *
     * @return array Liste des statuts de commande disponibles
     */
    public static function getStatutsCommande() {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("SELECT DISTINCT Statut_commande FROM commande");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}