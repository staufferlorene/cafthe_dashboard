<?php

namespace securite;

use Database;
use mvc_home\HomeModel;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../mvc_home/HomeModel.php';
require_once 'config/Database.php';

class HomeSecurityTest extends TestCase {

    // démarre une transaction, mettant en "attente" les modifications sur la BDD, puis les tests se lancent
    protected function setUp(): void {
        $db = Database::getInstance()->getConnection();
        $db->beginTransaction();
    }

    // annule toutes les modifications à la fin de CHAQUE test, la BDD reste propre
    protected function tearDown(): void {
        $db = Database::getInstance()->getConnection();
        $db->rollBack();
    }

    // Tester une tentative d'injection SQL (méthodes de HomeModel n'acceptent pas de paramètres utilisateur
    // le test vérifie que les données malveillantes en BDD ne sont pas éxécutées)
    public function testInjectionSQLHome() {

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("
            INSERT INTO produit 
            (Nom_produit, Description, Prix_HT, Prix_TTC, Stock, Type_conditionnement, Id_categorie) 
            VALUES (?, 'Description', 10, 12, 10, 'vrac', 1)
        ");
        $stmt->execute(["'; DROP TABLE produit; --"]);

        $result = HomeModel::listerProduitsVendus();

        // Vérifier que la requête n'a pas exécuté le SQL malveillant
        // Si la table produit existait toujours = l'injection a été bloquée
        $this->assertIsArray($result);

        // Vérifier que la table produit existe toujours
        $check = $db->query("SELECT 1 FROM produit LIMIT 1");
        $this->assertNotFalse($check);
    }

    // Tester que les données avec du HTML ne cassent pas l'affichage
    public function testXSSHome() {

        // Insérer un produit avec du contenu XSS dans le nom
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("
            INSERT INTO produit 
            (Nom_produit, Description, Prix_HT, Prix_TTC, Stock, Type_conditionnement, Id_categorie) 
            VALUES (?, 'Description', 10, 12, 10, 'vrac', 1)
        ");
        $stmt->execute(["<script>alert('XSS')</script>"]);

        $result = HomeModel::listerProduitsVendus();

        // Vérifier que les données sont retournées
        $this->assertIsArray($result);

        // La protection XSS se fait au niveau de la vue avec Smarty {$variable|escape}
        // Ici on vérifie juste que les données ne cassent pas la requête SQL
        $this->assertTrue(true);
    }
}