<?php

namespace mvc_vendeur;
use Database;
use PDO;
use PDOException;

require_once 'config/Database.php';

class VendeurModel {

    // propriétés privées (encapsulation)
    private $pdo;
    private $Id_vendeur; // id unique de la BDD
    private $Nom_vendeur;
    private $Prenom_vendeur;
    private $Role;
    private $Mail_vendeur;
    private $Mdp_vendeur;


    // Constructeur : initialisation du vendeur
    // public function => pour que la fonction soit accessible partout
    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    // Getter pour l'id
    public function getId() {
        return $this->Id_vendeur;
    }

    // Getter et setter pour le Nom_vendeur
    public function getNom_vendeur() {
        return $this->Nom_vendeur;
    }

    public function setNom_vendeur($Nom_vendeur) {
        $this->Nom_vendeur = $Nom_vendeur;
    }

    // Getter et setter pour le Prenom_vendeur
    public function getPrenom_vendeur() {
        return $this->Prenom_vendeur;
    }

    public function setPrenom_vendeur($Prenom_vendeur) {
        $this->Prenom_vendeur = $Prenom_vendeur;
    }

    // Getter et setter pour le Role
    public function getRole() {
        return $this->Role;
    }

    public function setRole($Role) {
        $this->Role = $Role;
    }

    // Getter et setter pour le Mail_vendeur
    public function getMail_vendeur() {
        return $this->Mail_vendeur;
    }

    public function setMail_vendeur($Mail_vendeur) {
        $this->Mail_vendeur = $Mail_vendeur;
    }

    // Getter et setter pour le Mdp_vendeur
    public function getMdp_vendeur() {
        return $this->Mdp_vendeur;
    }

    public function setMdp_vendeur($Mdp_vendeur) {
        $this->Mdp_vendeur = $Mdp_vendeur;
    }


    /*********************
     *********************
     *
     * METHODES
     *
     *********************
     ********************/

    /**
     * Liste tous les vendeurs
     *
     * @return array Liste des vendeurs
     */

    public static function lister() {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();

        // Récupération des infos dans la BDD
        $stmt = $db->prepare("SELECT * FROM vendeur");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Vérifie si l'adresse mail du vendeur existe déjà dans la base de donnée
     *
     * @param string $mail Mail du vendeur
     *
     * @return bool Retourne true si mail existe sinon false
     */
    public static function existeMail($mail) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT COUNT(*) FROM vendeur WHERE Mail_vendeur = ?");
        $stmt->execute([$mail]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     *  Ajoute un vendeur dans la base de données
     *
     * @param string $Nom_vendeur Nom du vendeur
     * @param string $Prenom_vendeur Prénom du vendeur
     * @param string $Role Rôle du vendeur
     * @param string $Mail_vendeur Mail du vendeur
     * @param string $Mdp_vendeur Mot de passe du vendeur
     *
     * @return string|null Retourne null si succès, ou un message d'erreur en cas d’échec
     */
    public static function ajouter($Nom_vendeur, $Prenom_vendeur, $Role, $Mail_vendeur, $Mdp_vendeur) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();
        try {
            // Hachage du mdp (bcrypt et 10 salage)
            $hashedPassword = password_hash($Mdp_vendeur, PASSWORD_BCRYPT, ['cost' => 10]);

            // Préparation de la requête
            $stmt = $db->prepare("INSERT INTO vendeur (Nom_vendeur, Prenom_vendeur, Role, Mail_vendeur, Mdp_vendeur) VALUES (?, ?, ?, ?, ?)");

            // Exécution
            $stmt->execute([$Nom_vendeur, $Prenom_vendeur, $Role, $Mail_vendeur, $hashedPassword]);
            return null; // Pas d'erreur
        } catch (PDOException $e) {
            return $e->getMessage(); // Retourne le message d'erreur
        }
    }

    /**
     * Met à jour un vendeur existant dans la base de données
     *
     * @param string $Nom_vendeur Nom du vendeur
     * @param string $Prenom_vendeur Prénom du vendeur
     * @param string $Role Rôle du vendeur
     * @param string $Mail_vendeur Mail du vendeur
     * @param string $Mdp_vendeur Mot de passe du vendeur
     * @param int $Id_vendeur Identifiant du vendeur à modifier
     *
     * @return void
     */

    public static function modifier($Nom_vendeur, $Prenom_vendeur, $Role, $Mail_vendeur, $Mdp_vendeur, $Id_vendeur) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();

        // Hachage du mdp (bcrypt et 10 salage)
        $hashedPassword = password_hash($Mdp_vendeur, PASSWORD_BCRYPT, ['cost' => 10]);

        // Màj
        $stmt = $db->prepare("UPDATE vendeur SET Nom_vendeur=?, Prenom_vendeur=?, Role=?, Mail_vendeur=?, Mdp_vendeur=? WHERE Id_vendeur=?");
        $stmt->execute([$Nom_vendeur, $Prenom_vendeur, $Role, $Mail_vendeur, $hashedPassword, $Id_vendeur]);
    }

    /**
     * Supprime un vendeur de la base de données
     *
     * @param int $Id_vendeur Identifiant du vendeur à supprimer
     *
     * @return bool Retourne true si la fonction de suppression a réussi, false sinon
     */

    public static function delete($Id_vendeur) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM vendeur WHERE Id_vendeur = ?");
        return $stmt->execute([$Id_vendeur]);
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

    /**
     * Charge un vendeur par son identifiant
     *
     * @param int $Id_vendeur Identifiant du vendeur
     *
     * @return VendeurModel|null Retourne un objet VendeurModel ou null si non trouvé
     */

    public static function loadById(int $Id_vendeur) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();

        // Récupération des infos dans la BDD
        $stmt = $db->prepare("SELECT * FROM vendeur WHERE Id_vendeur = ?");
        $stmt->execute([$Id_vendeur]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $vendeur = new VendeurModel();
            $vendeur->Id_vendeur = $data['Id_vendeur'];
            $vendeur->setNom_vendeur($data['Nom_vendeur']);
            $vendeur->setPrenom_vendeur($data['Prenom_vendeur']);
            $vendeur->setRole($data['Role']);
            $vendeur->setMail_vendeur($data['Mail_vendeur']);
            $vendeur->setMdp_vendeur($data['Mdp_vendeur']);
            return $vendeur;
        }

        // sinon on retourne null
        return null;
    }

    /**
     * Vérifie si le vendeur a au moins une commande qui lui est rattachée
     *
     * @param $Id_vendeur
     * @return bool
     */
    public static function haveOrder($Id_vendeur) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("SELECT COUNT(*) FROM commande WHERE Id_vendeur = ?");
        $stmt->execute([$Id_vendeur]);
        return $stmt->fetchColumn() > 0;
    }
}