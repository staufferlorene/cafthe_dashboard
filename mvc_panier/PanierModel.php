<?php

namespace mvc_panier;
use Database;
use PDO;
use PDOException;

require_once 'config/Database.php';

/**
 * Modèle pour la gestion du panier d'achat
 *
 * Cette classe gère les opérations liées au panier :
 * - Calcul des totaux (HT, TTC, TVA)
 * - Gestion des produits et clients
 * - Enregistrement des commandes en base de données
 */

class PanierModel {

    // propriétés privées (encapsulation)
    private $pdo;
    private $Id_produit; // id unique de la BDD
    private $Prix_TTC;
    private $Prix_HT;
    private $Tva_categorie;

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
     * Calcule les totaux du panier (HT, TTC, TVA)
     *
     * @param array $panier Contenu du panier avec les produits et leurs quantités
     *
     * @return array Tableau contenant 'totalHT', 'totalTTC' et 'totalTVA'
     */
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

    /**
     * Supprime un produit du panier en session
     *
     * @param int $id Identifiant du produit à supprimer
     *
     * @return void
     */
    public static function delete($id) {
        unset($_SESSION['panier'][$id]);
    }

    /**
     * Liste tous les clients de la base de données
     *
     * @return array Liste des clients
     */
    public static function listerClient() {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();

        // Récupération des infos dans la BDD
        $stmt = $db->prepare("SELECT * FROM client");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Enregistre la commande et ses lignes en base de données
     *
     * Cette méthode :
     * - Crée une nouvelle commande avec les montants totaux
     * - Lie la commande au client et au vendeur
     * - Insère toutes les lignes de commande (produits du panier)
     * - Utilise les données de session : panier, client, montants, venderu
     *
     * @return string|null Retourne null si succès, ou un message d'erreur en cas d'échec
     */
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