<?php

require_once 'Database.php';

class Produits {

    // propriétés privées (encapsulation)
    private $pdo;
    private $id_produits; // id unique de la BDD
    private $nom;
    private $prix;
    private $stock;


    // Constructeur : initialisation du produit
    // public function => pour que la fonction soit accessible partout
    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    // Getter pour l'id
    public function getId() {
        return $this->id_produits;
    }

    // Getter pour le nom
    public function getNom() {
        return $this->nom;
    }

    // Getter pour le prix
    public function getPrix() {
        return $this->prix;
    }

    // Getter pour le stock
    public function getStock() {
        return $this->stock;
    }

    // Get des détails du produit
    public function getDetails() {
        return "Produit : " .$this->id_produits . $this->nom . " " . $this->prix . " " . $this->stock;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function setPrix($prix) {
        $this->prix = $prix;
    }

    public function setStock($stock) {
        $this->stock = $stock;
    }

    public function showDetails() {
        echo "Produit : " .$this->id_produits . $this->nom . " " . $this->prix . " " . $this->stock;
    }

    //Méthode pour charger les produits provenant de la BDD
    /**
     * Charger un produit depuis la BDD via son ID
     *
     * @param int id_produits id de du produit
     * @return array|null retourne l'objet produit (ou rien si non trouvé)
     */

    public static function lister() {
        /* public static function lister(int $id_produits) {*/
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();

        // Récupération des infos dans la BDD
        $stmt = $db->prepare("SELECT * FROM produits");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ajout d'un produit dans la BDD
    public static function ajouter ($nom, $prix, $stock) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();
        // Ajout
        $stmt = $db->prepare("INSERT INTO produits (nom, prix, stock) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $prix, $stock]);
    }

    // Modification d'un produit dans la BDD
    public static function modifier ($nom, $prix, $stock, $id_produits) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();
        // Màj
        $stmt = $db->prepare("UPDATE produits SET nom=?, prix=?, stock=? WHERE id_produits=?");
        $stmt->execute([$nom, $prix, $stock, $id_produits]);
    }

    public static function delete($id_produits) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM produits WHERE id_produits = ?");
        return $stmt->execute([$id_produits]);
    }

    public function save()
    {
        $db = Database::getInstance()->getConnection();

        if ($this->id_produits === null) {
            //insertion
            $stmt = $db->prepare("INSERT INTO produits(nom, prix, stock) VALUE(?,?,?)");
            $stmt->execute([$this->nom, $this->prix, $this->stock]);

            //Recuperation de l'id généré par MySQL
            $this->id_produits = $db->lastInsertId();
        } else {
            //Mise à jour si la voiture existe déjà
            $stmt = $db->prepare("UPDATE produits SET nom =?, prix =?, stock=? WHERE id_produits = ?");
            $stmt->execute([$this->nom, $this->prix, $this->stock, $this->id_produits]);
        }
    }

    public static function loadById(int $id_produits) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();

        // Récupération des infos dans la BDD
        $stmt = $db->prepare("SELECT * FROM produits WHERE id_produits = ?");
        $stmt->execute([$id_produits]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $produit = new Produits();
            $produit->id_produits = $data['id_produits'];
            $produit->setNom($data['nom']);
            $produit->setPrix($data['prix']);
            $produit->setStock($data['stock']);
            return $produit;
        }

        // sinon on retourne null
        return null;
    }

}