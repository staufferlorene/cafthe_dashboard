<?php

namespace template;
use Database;
use PDO;
use PDOException;

require_once 'config/Database.php';

class TopBarModel {

    /**
     * Récupère les produits dont le stock est inférieur ou égal à 5
     *
     * @return array Liste des produits en alerte de stock (id, nom et quantité)
     */
    public static function getStockAlert() {
        // On récupère PDO via la Class Database
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("SELECT Id_produit, Nom_produit, Stock FROM produit WHERE Stock <= 5 ORDER BY Stock ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}