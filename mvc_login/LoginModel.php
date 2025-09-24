<?php


namespace mvc_login;
use Database;
use PDO;

require_once 'config/Database.php';

class LoginModel
{
    public static function getVendeurParMail($mail) {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();

        // Préparation de la requête pour chercher l'utilisateur
        $stmt = $db->prepare("SELECT * FROM vendeur WHERE Mail_vendeur = ?");
        $stmt->execute([$mail]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function verifierMotDePasse($mdp, $hash) {
        // Vérifie si le mot de passe correspond au mdp haché en BDD
        return password_verify($mdp, $hash);
    }
}