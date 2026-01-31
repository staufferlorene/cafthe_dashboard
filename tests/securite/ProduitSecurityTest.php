<?php

namespace securite;

use Database;
use mvc_produit\ProduitModel;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../mvc_produit/ProduitModel.php';
require_once 'config/Database.php';

class ProduitSecurityTest extends TestCase {

    // fonction protégée servant à ne pas polluer ma BDD avec les tests
    protected function setUp(): void {
        $db = Database::getInstance()->getConnection();
        $db->beginTransaction();
    }

    protected function tearDown(): void {
        $db = Database::getInstance()->getConnection();
        $db->rollBack();
    }


    // Tester une injection SQL lors de l'ajout d'un produit
    public function testInjectionSQL() {
        $countBefore = count(ProduitModel::lister());

        ProduitModel::ajouter(
            "' OR 1=1 --",
            "desc",
            10,
            8,
            5,
            "vrac",
            1
        );

        $countAfter = count(ProduitModel::lister());

        // Une seule ligne ajoutée, pas plus
        $this->assertEquals($countBefore + 1, $countAfter);
    }
}