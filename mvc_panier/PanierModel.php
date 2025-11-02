<?php

namespace mvc_panier;
use Database;
use PDO;
use PDOException;

require_once 'config/Database.php';

class PanierModel {

    // propriétés privées (encapsulation)
    private $pdo;
    private $Id_produit; // id unique de la BDD
    private $Prix_TTC;
    private $Prix_HT;
    private $Tva_categorie;


    // Constructeur : initialisation du produit
    // public function => pour que la fonction soit accessible partout
    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    // Getter pour l'id
    public function getId() {
        return $this->Id_produit;
    }

    // Getter et setter pour le Prix_TTC
    public function getPrix_TTC() {
        return $this->Prix_TTC;
    }

    public function setPrix_TTC($Prix_TTC) {
        $this->Prix_TTC = $Prix_TTC;
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

    public static function calculTotaux($panier) {
        $totalHT = 0;
        $totalTTC = 0;

        // Calcul des totaux HT et TTC sur tous les produits du panier
        foreach ($panier as $produit) {
            $totalHT += (float)$produit['prixht'] * (int)$produit['quantite'];
            $totalTTC += (float)$produit['prixttc'] * (int)$produit['quantite'];
        }

        // Calcul de la TVA
        $totalTVA = $totalTTC - $totalHT;

        return [
            'totalHT' => $totalHT,
            'totalTTC' => $totalTTC,
            'totalTVA' => $totalTVA
        ];
    }

    public static function delete($id) {
        unset($_SESSION['panier'][$id]);
    }
}