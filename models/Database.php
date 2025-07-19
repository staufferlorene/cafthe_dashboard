<?php

/* class Database /!\ class toujours avec une majuscule
* Se connecter à la base de données
 * Bien gérer les ressources (pattern Singleton) (pattern = chose qui se répète)
 * simplifier l'utilisation de PDO
 */
class Database
{

    // propriété privée - instance unique de la connexion
    // (static = instance existe même si l'objet n'a pas été crée : but limiter qu'il y ait plusieurs instances en même temps)
    private static $instance = null;

    // pour stocker l'objet $pdo (pdo permet la connexion à la BDD)
    private $pdo;

    // Constructeur privé (il ne peut être appelé qu'une fois)
    private function __construct() {

        // Configuration de la base de données
        $host = "localhost";
        $dbname = "brief_crud";
        $user = "root";
        $pass = "";

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e){
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null){
            self::$instance = new Database();
        }

        return self::$instance;
    }

    public function getConnection() {
        // Retourne l'objet PDO. Pourquoi ? Pour pouvoir faire des requêtes
        return $this->pdo;
    }
}

// Exemple pour appeler cette classe
// $db = Database::getInstance()->getConnection();