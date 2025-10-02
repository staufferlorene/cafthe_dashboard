<?php


namespace mvc_profil;
use Database;
use PDO;
use PDOException;

require_once 'config/Database.php';

///////////////////////
// EST-CE QUE JE PEUX SUPPRIMER LE MDP DANS LOADBYID ?? JE PENSE QUE OUI JE N'AFFICHE PAS LE MDP !!
///////////////////////



class ProfilModel {

    // propriétés privées (encapsulation)
    private $pdo;
    private $Id_vendeur; // id unique de la BDD
    private $Nom_vendeur;
    private $Prenom_vendeur;
    private $Mail_vendeur;
    private $Mdp_vendeur;


    // Constructeur : initialisation du profil
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

    public static function modifier($Nom_vendeur, $Prenom_vendeur, $Mail_vendeur, $Id_vendeur) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();

        // Màj
        $stmt = $db->prepare("UPDATE vendeur SET Nom_vendeur=?, Prenom_vendeur=?, Mail_vendeur=? WHERE Id_vendeur=?");
        $stmt->execute([$Nom_vendeur, $Prenom_vendeur, $Mail_vendeur, $Id_vendeur]);
    }
}