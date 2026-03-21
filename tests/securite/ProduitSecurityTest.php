<?php

namespace securite;

use Config\Database;
use mvc_produit\ProduitModel;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

require_once __DIR__ . '/../../vendor/autoload.php';

class ProduitSecurityTest extends TestCase {

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

    // Data provider avec plusieurs payloads d'injection SQL (chaque payload représente une tentative d'attaque différente)
    public static function payloadsProvider(): array {
        return [
            'OR 1=1'        => ["' OR 1=1 --"],
            'UNION SELECT'  => ["' UNION SELECT * FROM vendeur --"],
            'DROP TABLE'    => ["'; DROP TABLE produit; --"],
            'Double quote'  => ['" OR ""="'],
            'Comment'       => ["admin'/*"],
            'Stacked query' => ["'; INSERT INTO vendeur VALUES (999,'hack','hack','admin','hack@hack.com','hack'); --"],
        ];
    }

    // Tester l'injection SQL : vérifier qu'une ligne maximum est ajoutée (pas d'injection)
    #[DataProvider('payloadsProvider')]
    public function testInjectionSQLProduct(string $payload) {
        $db = Database::getInstance()->getConnection();

        // compter avant injection
        $countsBefore = [
            'vendeur' => (int)$db->query("SELECT COUNT(*) FROM vendeur")->fetchColumn(),
            'produit' => (int)$db->query("SELECT COUNT(*) FROM produit")->fetchColumn(),
            'client' => (int)$db->query("SELECT COUNT(*) FROM client")->fetchColumn(),
            'commande' => (int)$db->query("SELECT COUNT(*) FROM commande")->fetchColumn(),
        ];

        // faire l'injection
        ProduitModel::ajouter($payload, "ceci est la description", 10, 8, 5, "vrac", 1);

        // Vérifier qu'AUCUNE injection n'a réussi

        // vérifier que les tables existent toujours
        foreach (['vendeur', 'produit', 'client', 'commande'] as $table) {
            try {
                $db->query("SELECT 1 FROM {$table} LIMIT 1");
                $this->assertTrue(true, "La table {$table} existe toujours");
            } catch (\PDOException $e) {
                $this->fail("La table {$table} a été supprimée : {$payload}");
            }
        }

        // Vérifier que le nombre de lignes dans les différentes tables n'a PAS changé (sauf pour produit ou c'est +1 si création réussi sinon +0)
        // compter les lignes APRES l'injection
        $countsAfter = [
            'vendeur' => (int)$db->query("SELECT COUNT(*) FROM vendeur")->fetchColumn(),
            'produit' => (int)$db->query("SELECT COUNT(*) FROM produit")->fetchColumn(),
            'client' => (int)$db->query("SELECT COUNT(*) FROM client")->fetchColumn(),
            'commande' => (int)$db->query("SELECT COUNT(*) FROM commande")->fetchColumn(),
        ];

        $this->assertEquals($countsBefore['vendeur'], $countsAfter['vendeur']);
        $this->assertLessThanOrEqual($countsBefore['produit'] + 1, $countsAfter['produit']);
        $this->assertEquals($countsBefore['client'], $countsAfter['client']);
        $this->assertEquals($countsBefore['commande'], $countsAfter['commande']);
    }

    // Tester XSS (CROSS-SITE SCRIPTING) dans les champs texte
    public function testXSSProduct() {

        ProduitModel::ajouter(
            "<script>alert('XSS')</script>",
            'ceci est la description du produit ajouté pour test',
            10.50,
            10,
            30,
            'vrac',
            1
        );

        // récupération de l'id produit généré
        $db = Database::getInstance()->getConnection();
        $idProduct = $db->lastInsertId();

        // récupérer le produit
        $product = ProduitModel::loadById($idProduct);

        // Le nom est stocké tel quel en BDD (pas d'échappement nécessaire ici)
        $this->assertEquals("<script>alert('XSS')</script>", $product->getNom_produit());
        // La protection XSS se fait au niveau de la vue avec Smarty {$variable|escape}
        // Le test vérifie que les données ne cassent pas la BDD
    }
}