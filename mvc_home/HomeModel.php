<?php

namespace mvc_home;
use Database;
use PDO;
use PDOException;

require_once 'config/Database.php';

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


    // Constructeur : initialisation du produit
    // public function => pour que la fonction soit accessible partout
    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    // Getter pour l'id
    public function getId() {
        return $this->Id_produit;
    }

    // Getter et setter pour le Nom_produit
    public function getNom_produit() {
        return $this->Nom_produit;
    }

    public function setNom_produit($Nom_produit) {
        $this->Nom_produit = $Nom_produit;
    }

    // Getter et setter pour le Prix_TTC
    public function getPrix_TTC() {
        return $this->Prix_TTC;
    }

    public function setPrix_TTC($Prix_TTC) {
        $this->Prix_TTC = $Prix_TTC;
    }

    // Getter et setter pour le Stock
    public function getStock() {
        return $this->Stock;
    }

    public function setStock($Stock) {
        $this->Stock = $Stock;
    }

    // Getter et setter pour la Description
    public function getDescription() {
        return $this->Description;
    }

    public function setDescription($Description) {
        $this->Description = $Description;
    }

    // Getter et setter pour le Prix_HT
    public function getPrix_HT() {
        return $this->Prix_HT;
    }

    public function setPrix_HT($Prix_HT) {
        return $this->Prix_HT = $Prix_HT;
    }

    // Getter et setter pour la TVA
    public function getTva_categorie() {
        return $this->Tva_categorie;
    }

    public function setTva_categorie($Tva_categorie) {
        return $this->Tva_categorie = $Tva_categorie;
    }

    // Getter et setter pour la Catégorie
    public function getNom_categorie() {
        return $this->Nom_categorie;
    }

    public function setNom_categorie($Nom_categorie) {
        return $this->Nom_categorie = $Nom_categorie;
    }

    // Getter et setter pour le Type de conditionnement
    public function getType_conditionnement() {
        return $this->Type_conditionnement;
    }

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
     * xxxxxxxxx
     * Liste tous les produits avec leur catégorie associée
     *
     * @return array Liste des produits et leurs catégories
     */

    public static function listerProduitsVendus() {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();

        // Récupération des infos dans la BDD
        $stmt = $db->prepare(
            "SELECT p.Nom_produit, SUM(l.Quantite_produit_ligne_commande) AS qte_totale_vendu
            FROM ligne_commande AS l
            JOIN produit AS p ON p.Id_produit = l.Id_produit
            GROUP BY l.Id_produit
            ORDER BY qte_totale_vendu DESC
            LIMIT 10"
        );
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}