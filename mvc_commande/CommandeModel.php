<?php

namespace mvc_commande;
use Database;
use PDO;
use PDOException;

require_once 'config/Database.php';

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


    // Constructeur : initialisation de la commande
    // public function => pour que la fonction soit accessible partout
    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    // Getter pour l'id
    public function getId() {
        return $this->Id_commande;
    }

    // Getter et setter pour le nom du client
    public function getNom_client() {
        return $this->Nom_client;
    }
    public function setNom_client($Nom_client) {
        $this->Nom_client = $Nom_client;
    }

    // Getter et setter pour le prénom du client
    public function getPrenom_client() {
        return $this->Prenom_client;
    }
    public function setPrenom_client($Prenom_client) {
        $this->Prenom_client = $Prenom_client;
    }

    // Getter et setter pour l'adresse de livraison de la commande
    public function getAdresse_livraison() {
        return $this->Adresse_livraison;
    }
    public function setAdresse_livraison($Adresse_livraison) {
        $this->Adresse_livraison = $Adresse_livraison;
    }

    // Getter et setter pour le Statut de la commande
    public function getStatut_commande() {
        return $this->Statut_commande;
    }
    public function setStatut_commande($Statut_commande) {
        $this->Statut_commande = $Statut_commande;
    }

    // Getter et setter pour la date de la commande
    public function getDate_commande() {
        return $this->Date_commande;
    }
    public function setDate_commande($Date_commande) {
        $this->Date_commande = $Date_commande;
    }

    // Getter et setter pour le Montant TTC de la commande
    public function getMontant_commande_TTC() {
        return $this->Montant_commande_TTC;
    }
    public function setMontant_commande_TTC($Montant_commande_TTC) {
        $this->Montant_commande_TTC = $Montant_commande_TTC;
    }

    // Getter et setter pour le Nom du produit de la commande
    public function getNom_produit() {
        return $this->Nom_produit;
    }
    public function setNom_produit($Nom_produit) {
        $this->Nom_produit = $Nom_produit;
    }

    // Getter et setter pour la Quantite de produit de la commande
    public function getQuantite_produit_ligne_commande() {
        return $this->Quantite_produit_ligne_commande;
    }
    public function setQuantite_produit_ligne_commande($Quantite_produit_ligne_commande) {
        $this->Quantite_produit_ligne_commande = $Quantite_produit_ligne_commande;
    }

    // Getter et setter pour le Montant HT de la commande
    public function getMontant_commande_HT() {
        return $this->Montant_commande_HT;
    }
    public function setMontant_commande_HT($Montant_commande_HT) {
        $this->Montant_commande_HT = $Montant_commande_HT;
    }

    // Getter et setter pour le Montant TTC de la commande
    public function getMontant_TVA() {
        return $this->Montant_TVA;
    }
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

    // Modification d'un produit dans la BDD

    /**
     * Met à jour le statut d'une commande existante dans la base de données
     *
     * @param string $Statut_commande Statut de la commande
     * @param int $Id_commande Identifiant de la commande
     *
     * @return void
     */

    public static function modifier($Statut_commande, $Id_commande) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();
        // Màj
        $stmt = $db->prepare("UPDATE commande SET Statut_commande=? WHERE Id_commande=?");
        $stmt->execute([$Statut_commande, $Id_commande]);
    }

    /**
     * Charge une commande par son identifiant
     *
     * @param int $Id_commande Identifiant de la commande
     * @return CommandeModel|null Retourne un objet CommandeModel ou null si non trouvé
     */

    public static function loadById(int $Id_commande) {
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
            $commande->setNom_produit($data['Nom_produit']);
            $commande->setQuantite_produit_ligne_commande($data['Quantite_produit_ligne_commande']);
            $commande->setMontant_commande_HT($data['Montant_commande_HT']);
            $commande->setMontant_TVA($data['Montant_TVA']);

            return $commande;
        }

        // sinon on retourne null
        return null;
    }
}