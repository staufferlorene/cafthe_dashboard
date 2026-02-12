<?php


namespace mvc_login;
use Database;
use PDO;

require_once 'config/Database.php';

/**
 * Modèle pour la gestion de l'authentification
 *
 * Cette classe gère les opérations liées à la connexion des vendeurs :
 * - Récupération des informations d'un vendeur grâce à son email
 * - Vérification du mot de passe
 */

class LoginModel {

    /**
     * Récupère un vendeur par son adresse email
     *
     * @param string $mail Adresse email du vendeur
     *
     * @return array|false Retourne les données du vendeur si trouvé, false sinon
     */
    public static function getVendeurParMail($mail) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();

        // Préparation de la requête pour chercher l'utilisateur
        $stmt = $db->prepare("SELECT * FROM vendeur WHERE Mail_vendeur = ?");
        $stmt->execute([$mail]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Vérifie si un mot de passe correspond à son hash
     *
     * @param string $mdp Mot de passe en clair à vérifier
     * @param string $hash Hash du mot de passe stocké en base de données
     *
     * @return bool Retourne true si le mot de passe correspond, false sinon
     */
    public static function verifierMotDePasse($mdp, $hash) {
        // Vérifie si le mot de passe correspond au mdp haché en BDD
        return password_verify($mdp, $hash);
    }
}