<?php

namespace securite;

use Database;
use mvc_commande\CommandeModel;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

require_once __DIR__ . '/../../mvc_commande/CommandeModel.php';
require_once 'config/Database.php';

class CommandeSecurityTest extends TestCase {

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

    // Tester l'injection SQL : vérifier qu'aucune ligne n'a été ajoutée (pas d'injection)
    #[DataProvider('payloadsProvider')]
    public function testInjectionSQLCommand(string $payload) {

        $db = Database::getInstance()->getConnection();

        // compter avant injection
        $countsBefore = [
            'vendeur' => (int)$db->query("SELECT COUNT(*) FROM vendeur")->fetchColumn(),
            'produit' => (int)$db->query("SELECT COUNT(*) FROM produit")->fetchColumn(),
            'client' => (int)$db->query("SELECT COUNT(*) FROM client")->fetchColumn(),
            'commande' => (int)$db->query("SELECT COUNT(*) FROM commande")->fetchColumn(),
        ];

        // faire l'injection
        CommandeModel::modifier($payload, 1);

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

        // Vérifier que le nombre de lignes dans les différentes tables n'a PAS changé
        // compter les lignes APRES l'injection
        $countsAfter = [
            'vendeur' => (int)$db->query("SELECT COUNT(*) FROM vendeur")->fetchColumn(),
            'produit' => (int)$db->query("SELECT COUNT(*) FROM produit")->fetchColumn(),
            'client' => (int)$db->query("SELECT COUNT(*) FROM client")->fetchColumn(),
            'commande' => (int)$db->query("SELECT COUNT(*) FROM commande")->fetchColumn(),
        ];

        $this->assertEquals($countsBefore['vendeur'], $countsAfter['vendeur']);
        $this->assertEquals($countsBefore['produit'], $countsAfter['produit']);
        $this->assertEquals($countsBefore['client'], $countsAfter['client']);
        $this->assertEquals($countsBefore['commande'], $countsAfter['commande']);
    }

    // Tester XSS (CROSS-SITE SCRIPTING) dans les champs texte
    public function testXSSCommand() {

        CommandeModel::modifier(
            "<script>alert('XSS')</script>",
            1
        );

        // récupérer la commande
        $command = CommandeModel::loadByIdClient(1);

        // Le nom est stocké tel quel en BDD (pas d'échappement nécessaire ici)
        $this->assertEquals("<script>alert('XSS')</script>", $command->getStatut_commande());
        // La protection XSS se fait au niveau de la vue avec Smarty {$variable|escape}
        // Le test vérifie que les données ne cassent pas la BDD
    }
}