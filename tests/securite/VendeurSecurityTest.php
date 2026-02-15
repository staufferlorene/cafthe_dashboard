<?php

namespace securite;

use Database;
use mvc_vendeur\VendeurModel;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

require_once __DIR__ . '/../../mvc_vendeur/VendeurModel.php';
require_once 'config/Database.php';

class VendeurSecurityTest extends TestCase {

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
    public function testInjectionSQLVendeur(string $payload) {
        $countBefore = count(VendeurModel::lister());

        VendeurModel::ajouter(
            $payload,
            'Jeanne',
            'vendeur',
            'mail.vendeur@email.com',
            'Motdepassevendeur123'
        );

        $countAfter = count(VendeurModel::lister());

        // 1 ou 0 ligne ajoutée = pas d'injection
        $this->assertLessThanOrEqual($countBefore + 1, $countAfter);
    }

    // Tester XSS (CROSS-SITE SCRIPTING) dans les champs texte
    public function testXSSProduct() {

        VendeurModel::ajouter(
            "<script>alert('XSS')</script>",
            'Jeanne',
            'vendeur',
            'mail.vendeur@email.com',
            'Motdepassevendeur123'
        );

        // récupération de l'id vendeur généré
        $db = Database::getInstance()->getConnection();
        $idVendeur = $db->lastInsertId();

        // récupérer le vendeur
        $vendeur = VendeurModel::loadById($idVendeur);

        // Le nom est stocké tel quel en BDD (pas d'échappement nécessaire ici)
        $this->assertEquals("<script>alert('XSS')</script>", $vendeur->getNom_vendeur());
        // La protection XSS se fait au niveau de la vue avec Smarty {$variable|escape}
        // Le test vérifie que les données ne cassent pas la BDD
    }


}