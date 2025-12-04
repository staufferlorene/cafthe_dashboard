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

    public static function listerClient() {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();

        // Récupération des infos dans la BDD
        $stmt = $db->prepare("SELECT * FROM client");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addDB() {
        try {
            // On récupère PDO via la Class Database
            $db = Database::getInstance()->getConnection();

            // Récupération du panier
            $totalHT = $_SESSION['montants']['totalHT'];
            $totalTVA = $_SESSION['montants']['totalTVA'];
            $totalTTC = $_SESSION['montants']['totalTTC'];

            // Récupération de l'id du client
            $idClient = $_SESSION['clientSession']['id'];

            // Récupération de l'id du vendeur
            $idVendeur = $_SESSION['utilisateur']['Id_vendeur'];

            $statutCommande = 'Livrée';
            $adresseLivraison = 'Vente réalisée en magasin';

            // Insertion de la commande
            $stmt = $db->prepare(
                "INSERT INTO commande (Date_commande, Statut_commande, Adresse_livraison, Montant_commande_HT, Montant_TVA, Montant_commande_TTC, Id_vendeur, Id_client) 
                VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([$statutCommande, $adresseLivraison, $totalHT, $totalTVA, $totalTTC, $idVendeur, $idClient]);

            // Récupérer l'id de la commande auto incrémenté
            $idCommande = $db->lastInsertId();

            // Insertion des lignes de commandes
            // Boucle pour enregistrer TOUTES les lignes de la commande

            $panier = $_SESSION['panier'];
            $i = 1;

            foreach ($panier as $produit) {

                $stmt = $db->prepare("INSERT INTO ligne_commande (Nombre_ligne_commande, Quantite_produit_ligne_commande, Prix_unitaire_ligne_commande, Id_commande, Id_produit) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$i, $produit['quantite'], $produit['prixht'], $idCommande, $produit['id']]);

                $i++;
            }
            return null; // Retourne null si succès pour gestion des erreurs dans le controlleur
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
}