<?php

namespace mvc_vendeur;
use Database;
use PDO;
use PDOException;

require_once 'config/Database.php';

/**
 * Modèle pour la gestion des vendeurs
 *
 * Cette classe gère les opérations en base de données liées aux vendeurs :
 * - CRUD (Create, Read, Update, Delete)
 * - Vérification de l'existence d'un email
 * - Gestion des rôles
 * - Vérification des liens avec les commandes
 */

class VendeurModel {

    // propriétés privées (encapsulation)
    private $pdo;
    private $Id_vendeur; // id unique de la BDD
    private $Nom_vendeur;
    private $Prenom_vendeur;
    private $Role;
    private $Mail_vendeur;
    private $Mdp_vendeur;


    /**
     * Constructeur : initialise la connexion à la base de données
     */
    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    /**
     * Récupère l'identifiant du vendeur
     *
     * @return int Identifiant du vendeur
     */
    public function getId() {
        return $this->Id_vendeur;
    }

    /**
     * Récupère le nom du vendeur
     *
     * @return string Nom du vendeur
     */
    public function getNom_vendeur() {
        return $this->Nom_vendeur;
    }

    /**
     * Définit le nom du vendeur
     *
     * @param string $Nom_vendeur Nom du vendeur
     *
     * @return void
     */
    public function setNom_vendeur($Nom_vendeur) {
        $this->Nom_vendeur = $Nom_vendeur;
    }

    /**
     * Récupère le prénom du vendeur
     *
     * @return string Prénom du vendeur
     */
    public function getPrenom_vendeur() {
        return $this->Prenom_vendeur;
    }

    /**
     * Définit le prénom du vendeur
     *
     * @param string $Prenom_vendeur Prénom du vendeur
     *
     * @return void
     */
    public function setPrenom_vendeur($Prenom_vendeur) {
        $this->Prenom_vendeur = $Prenom_vendeur;
    }

    /**
     * Récupère le rôle du vendeur
     *
     * @return string Rôle du vendeur (ex: Admin, Vendeur, Manager)
     */
    public function getRole() {
        return $this->Role;
    }

    /**
     * Définit le rôle du vendeur
     *
     * @param string $Role Rôle du vendeur
     *
     * @return void
     */
    public function setRole($Role) {
        $this->Role = $Role;
    }

    /**
     * Récupère l'email du vendeur
     *
     * @return string Email du vendeur
     */
    public function getMail_vendeur() {
        return $this->Mail_vendeur;
    }

    /**
     * Définit l'email du vendeur
     *
     * @param string $Mail_vendeur Email du vendeur
     *
     * @return void
     */
    public function setMail_vendeur($Mail_vendeur) {
        $this->Mail_vendeur = $Mail_vendeur;
    }

    /**
     * Récupère le mot de passe haché du vendeur
     *
     * @return string Mot de passe haché
     */
    public function getMdp_vendeur() {
        return $this->Mdp_vendeur;
    }

    /**
     * Définit le mot de passe haché du vendeur
     *
     * @param string $Mdp_vendeur Mot de passe haché
     *
     * @return void
     */
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
     * Le mot de passe est haché avec BCrypt avant stockage.
     *
     * @param string $Nom_vendeur Nom du vendeur
     * @param string $Prenom_vendeur Prénom du vendeur
     * @param string $Role Rôle du vendeur
     * @param string $Mail_vendeur Email du vendeur
     * @param string $Mdp_vendeur Mot de passe du vendeur (en clair, sera haché)
     * @param int $Id_vendeur Identifiant du vendeur à modifier
     *
     * @return string|null Retourne null si succès, ou un message d'erreur en cas d'échec
     */

    public static function modifier($Nom_vendeur, $Prenom_vendeur, $Role, $Mail_vendeur, $Mdp_vendeur, $Id_vendeur) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();
        try {
            // cas mdp modifié -> on le met à jour
            if ($Mdp_vendeur !== null) {
                // Hachage du nouveau mdp (bcrypt et 10 salage)
                $hashedPassword = password_hash($Mdp_vendeur, PASSWORD_BCRYPT, ['cost' => 10]);

                // Màj
                $stmt = $db->prepare("UPDATE vendeur SET Nom_vendeur=?, Prenom_vendeur=?, Role=?, Mail_vendeur=?, Mdp_vendeur=? WHERE Id_vendeur=?");
                $stmt->execute([$Nom_vendeur, $Prenom_vendeur, $Role, $Mail_vendeur, $hashedPassword, $Id_vendeur]);
            } else {
                // cas du mdp non modifié -> on màj tout sauf le mdp
                $stmt = $db->prepare("UPDATE vendeur SET Nom_vendeur=?, Prenom_vendeur=?, Role=?, Mail_vendeur=? WHERE Id_vendeur=?");
                $stmt->execute([$Nom_vendeur, $Prenom_vendeur, $Role, $Mail_vendeur, $Id_vendeur]);
            }
            return null; // Pas d'erreur
        } catch (PDOException $e) {
            return $e->getMessage(); // Retourne le message d'erreur
        }
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
     * @param int $Id_vendeur Identifiant du vendeur
     *
     * @return bool Retourne true si le vendeur a des commandes, false sinon
     */
    public static function haveOrder($Id_vendeur) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("SELECT COUNT(*) FROM commande WHERE Id_vendeur = ?");
        $stmt->execute([$Id_vendeur]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Récupère tous les rôles distincts de vendeurs
     *
     * @return array Liste des rôles disponibles
     */
    public static function getAllRole() {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("SELECT DISTINCT Role FROM vendeur");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}