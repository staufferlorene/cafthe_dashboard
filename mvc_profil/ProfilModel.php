<?php


namespace mvc_profil;
use Database;
use PDO;
use PDOException;

require_once 'config/Database.php';

/**
 * Modèle pour la gestion du profil vendeur
 *
 * Cette classe gère les opérations en base de données liées au profil
 * du vendeur connecté (lecture, modification d'informations, changement de mot de passe).
 */

class ProfilModel {

    // propriétés privées (encapsulation)
    private $pdo;
    private $Id_vendeur; // id unique de la BDD
    private $Nom_vendeur;
    private $Prenom_vendeur;
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
     * Charge un vendeur par son identifiant
     *
     * @param int $utilisateur Identifiant du vendeur
     *
     * @return ProfilModel|null Retourne un objet ProfilModel ou null si non trouvé
     */
    public static function loadById($utilisateur) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();

        // Récupération des infos dans la BDD
        $stmt = $db->prepare("SELECT * FROM vendeur WHERE Id_vendeur = ?");
        $stmt->execute([$utilisateur]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $vendeur = new ProfilModel();
            $vendeur->Id_vendeur = $data['Id_vendeur'];
            $vendeur->setNom_vendeur($data['Nom_vendeur']);
            $vendeur->setPrenom_vendeur($data['Prenom_vendeur']);
            $vendeur->setMail_vendeur($data['Mail_vendeur']);
            $vendeur->setMdp_vendeur($data['Mdp_vendeur']);
            return $vendeur;
        }

        // sinon on retourne null
        return null;
    }

    /**
     * Met à jour les informations personnelles d'un vendeur
     *
     * @param string $Nom_vendeur Nom du vendeur
     * @param string $Prenom_vendeur Prénom du vendeur
     * @param string $Mail_vendeur Email du vendeur
     * @param int $Id_vendeur Identifiant du vendeur à modifier
     *
     * @return string|null Retourne null si succès, ou un message d'erreur en cas d'échec
     */
    public static function modifier($Nom_vendeur, $Prenom_vendeur, $Mail_vendeur, $Id_vendeur) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();
        try {
            // Màj
            $stmt = $db->prepare("UPDATE vendeur SET Nom_vendeur=?, Prenom_vendeur=?, Mail_vendeur=? WHERE Id_vendeur=?");
            $stmt->execute([$Nom_vendeur, $Prenom_vendeur, $Mail_vendeur, $Id_vendeur]);
            return null; // Pas d'erreur
        } catch (PDOException $e) {
            return $e->getMessage(); // Retourne le message d'erreur
        }
    }

    /**
     * Modifie le mot de passe d'un vendeur
     *
     * Le mot de passe est haché avec BCrypt avant stockage
     *
     * @param string $ancien_mdp Ancien mot de passe (pour vérification)
     * @param string $nouveau_mdp Nouveau mot de passe
     * @param int $Id_vendeur Identifiant du vendeur
     *
     * @return string|null Retourne null si succès, sinon un message d'erreur
     */
    public static function modifierMdp($ancien_mdp, $nouveau_mdp, $Id_vendeur) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();

        try {
            // Récupération du mdp haché en BDD pour comparaison
            $stmt = $db->prepare("SELECT Mdp_vendeur FROM vendeur WHERE Id_vendeur = ?");
            $stmt->execute([$Id_vendeur]);
            $hash_actuel = $stmt->fetchColumn();

            if (!$hash_actuel) {
                return "Vendeur introuvable.";
            }

            // Vérifier que l'ancien mdp est correct
            if (!password_verify($ancien_mdp, $hash_actuel)) {
                return "Le mot de passe actuel est incorrect.";
            }

            // Hachage du mdp (bcrypt et 10 salage)
            $hashedPassword = password_hash($nouveau_mdp, PASSWORD_BCRYPT, ['cost' => 10]);

            // Màj
            $stmt = $db->prepare("UPDATE vendeur SET Mdp_vendeur = ? WHERE Id_vendeur = ?");
            $stmt->execute([$hashedPassword, $Id_vendeur]);

            return null; // Succès

        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
}