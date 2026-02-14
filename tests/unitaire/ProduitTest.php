<?php

namespace unitaire;

use Database;
use mvc_produit\ProduitModel;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../mvc_produit/ProduitModel.php';
require_once 'config/Database.php';

class ProduitTest extends TestCase {

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

    // Tester l'ajout de produit
    public function testCreateProduct() {

        // créer un nouveau produit
        $product = ProduitModel::ajouter(
            'ajout test',
            'ceci est la description du produit ajouté pour test',
            10.50,
            10,
            30,
            'vrac',
            1
        );

        // vérification si null est retourné, si oui insertion en BDD est OK
        $this->assertNull($product);
    }

    // Tester si la liste des produits retourne un tableau
    public function testReadProductReturnArray() {
        $product = ProduitModel::lister();
        $this->assertIsArray($product);
    }

    // Tester si la liste contient les champs attendus
    public function testReadProductContent() {

        // créer un nouveau produit au préalable
        ProduitModel::ajouter(
            'ajout test',
            'ceci est la description du produit ajouté pour test',
            10.50,
            10,
            30,
            'vrac',
            1
        );

        // lister les produits
        $product = ProduitModel::lister();

        // vérifier que le tableau n'est pas vide
        $this->assertNotEmpty($product);

        // vérifier la présence des clés spécifiées sur le 1er produit du tableau
        $this->assertArrayHasKey('Id_produit', $product[0]);
        $this->assertArrayHasKey('Nom_produit', $product[0]);
        $this->assertArrayHasKey('Description', $product[0]);
        $this->assertArrayHasKey('Prix_HT', $product[0]);
        $this->assertArrayHasKey('Prix_TTC', $product[0]);
        $this->assertArrayHasKey('Type_conditionnement', $product[0]);
        $this->assertArrayHasKey('Stock', $product[0]);
        $this->assertArrayHasKey('Id_categorie', $product[0]);
        $this->assertArrayHasKey('Nom_categorie', $product[0]);
        $this->assertArrayHasKey('Tva_categorie', $product[0]);
    }

    // Tester le chargement d'un produit par son id
    public function testLoadProductById() {

        // créer un nouveau produit au préalable
        ProduitModel::ajouter(
            'ajout test',
            'ceci est la description du produit ajouté pour test',
            10.50,
            10,
            30,
            'vrac',
            1
        );

        $product = ProduitModel::loadById(1);
        $this->assertInstanceOf(ProduitModel::class, $product);
    }

    // Tester chargement d'un produit inexistant (retourne null)
    public function testLoadProductNull() {
        $product = ProduitModel::loadById(999999999999);
        $this->assertNull($product);
    }

    // Tester la modification
    public function testUpdateProduct() {

        // créer un nouveau produit au préalable
        ProduitModel::ajouter(
            'ajout test',
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

        // modification du produit
        ProduitModel::modifier(
            'modification test',
            'description modifiée',
            11.50,
            11,
            25,
            'unitaire',
            2,
            $idProduct
        );

        // vérifier si les modifications ont été faites
        $productUpdate = ProduitModel::loadById($idProduct);

        $this->assertEquals('modification test', $productUpdate->getNom_produit());
        $this->assertEquals('description modifiée', $productUpdate->getDescription());
        $this->assertEquals(11.50, $productUpdate->getPrix_TTC());
        $this->assertEquals(11, $productUpdate->getPrix_HT());
        $this->assertEquals(25, $productUpdate->getStock());
        $this->assertEquals('unitaire', $productUpdate->getType_conditionnement());
        $this->assertEquals(2, $productUpdate->getId_categorie());
    }

    // Tester la suppression d'un produit
    public function testDeleteProduct() {

        // créer un nouveau produit au préalable
        ProduitModel::ajouter(
            'ajout test',
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

        // supprimer le produit
        $productDelete = ProduitModel::delete($idProduct);

        // vérifier si produit bien supprimé et plus présent en BDD
        $this->assertTrue($productDelete);
        $this->assertNull(ProduitModel::loadById($idProduct));
    }

    // Tester le retour booleen si produit est lié à une commande
    public function testHaveOrderProduct() {

        // créer un nouveau produit au préalable
        ProduitModel::ajouter(
            'ajout test',
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

        // vérifier si booleen
        $result = ProduitModel::haveOrder($idProduct);
        $this->assertIsBool($result);
    }
}