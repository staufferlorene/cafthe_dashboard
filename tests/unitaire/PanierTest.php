<?php

namespace unitaire;

use Database;
use mvc_panier\PanierModel;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../mvc_panier/PanierModel.php';
require_once 'config/Database.php';

class PanierTest extends TestCase {

    // démarre une transaction, mettant en "attente" les modifications sur la BDD, puis les tests se lancent
    protected function setUp(): void {
        $db = Database::getInstance()->getConnection();
        $db->beginTransaction();

        // initialiser la session si elle n'existe pas
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // nettoyer les variables de session avant chaque test
        $_SESSION['panier'] = [];
        $_SESSION['clientSession'] = [];
        $_SESSION['montants'] = [];
    }

    // annule toutes les modifications à la fin de CHAQUE test, la BDD reste propre
    protected function tearDown(): void {
        $db = Database::getInstance()->getConnection();
        $db->rollBack();

        // vider les sessions
        $_SESSION['panier'] = [];
        $_SESSION['clientSession'] = [];
        $_SESSION['montants'] = [];
    }

    // Tester si la liste des produits retourne un tableau
    public function testReadProductReturnArray() {
        $product = PanierModel::lister();
        $this->assertIsArray($product);
    }

    // Tester si la liste contient les champs attendus
    public function testReadProductContent() {

        // lister les produits
        $product = PanierModel::lister();

        // vérifier que le tableau n'est pas vide
        $this->assertNotEmpty($product);

        // vérifier la présence des clés spécifiées sur le 1er produit du tableau
        $this->assertArrayHasKey('Id_produit', $product[0]);
        $this->assertArrayHasKey('Nom_produit', $product[0]);
        $this->assertArrayHasKey('Prix_HT', $product[0]);
        $this->assertArrayHasKey('Prix_TTC', $product[0]);
        $this->assertArrayHasKey('Stock', $product[0]);
        $this->assertArrayHasKey('Nom_categorie', $product[0]);
    }

    // Tester le calcul de calculTotaux() avec un produit (prix non entier
    public function testCalculTotauxOneProduct() {
        $panier = [
            1 => [
                'id' => 1,
                'nom' => 'Produit test',
                'prixht' => 10.99,
                'prixttc' => 12.99,
                'quantite' => 2
            ]
        ];

        $result = PanierModel::calculTotaux($panier);

        // 2 x 10.99 = 21.98
        $this->assertEquals(21.98, $result['totalHT']);
        // 2 x 12.99 = 25.98
        $this->assertEquals(25.98, $result['totalTTC']);
        // 25.98 - 21.98 = 4
        $this->assertEquals(4.00, $result['totalTVA']);
    }

    // Tester le calcul de calculTotaux() avec plusieurs produits différents (prix entier)
    public function testCalculTotauxMultipleProduct() {
        $panier = [
            1 => ['id' => 1, 'nom' => 'Produit test 1', 'prixht' => 10.00, 'prixttc' => 12.00, 'quantite' => 2],
            2 => ['id' => 2, 'nom' => 'Produit test 2', 'prixht' => 5.00, 'prixttc' => 6.00, 'quantite' => 3],
            3 => ['id' => 3, 'nom' => 'Produit test 3', 'prixht' => 20.00, 'prixttc' => 24.00, 'quantite' => 1]
        ];

        $result = PanierModel::calculTotaux($panier);

        // (2x10) + (3x5) + (1x20) = 20 + 15 + 20 = 55
        $this->assertEquals(55.00, $result['totalHT']);
        // (2x12) + (3x6) + (1x24) = 24 + 18 + 24 = 66
        $this->assertEquals(66.00, $result['totalTTC']);
        // 66 - 55 = 11
        $this->assertEquals(11.00, $result['totalTVA']);
    }

    // Tester si calculTotaux() contient les champs attendus
    public function testCalculTotauxContent() {
        $panier = [];
        $result = PanierModel::calculTotaux($panier);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('totalHT', $result);
        $this->assertArrayHasKey('totalTTC', $result);
        $this->assertArrayHasKey('totalTVA', $result);
        // vérifier qu'il n'y a que les 3 clés "totalxx", pas plus ou moins
        $this->assertCount(3, $result);
    }

    // Tester la suppression d'un produit du panier en session
    public function testDeleteProductInPanier() {
        // créer un panier avec 2 produits au préalable
        $_SESSION['panier'] = [
            1 => ['id' => 1, 'nom' => 'Produit test 1', 'prixht' => 10, 'prixttc' => 12, 'quantite' => 2],
            2 => ['id' => 2, 'nom' => 'Produit test 2', 'prixht' => 5, 'prixttc' => 6, 'quantite' => 1]
        ];

        // supprimer le produit 1
        PanierModel::delete(1);

        // vérifier que le produit 1 n'existe plus
        $this->assertArrayNotHasKey(1, $_SESSION['panier']);
        // vérifier que le produit 2 existe toujours
        $this->assertArrayHasKey(2, $_SESSION['panier']);
    }

    // Tester si la liste des clients retourne un tableau
    public function testReadClientReturnArray() {
        $client = PanierModel::listerClient();
        $this->assertIsArray($client);
    }

    // Tester si la liste contient les champs attendus
    public function testReadClientContent() {

        // lister les clients
        $client = PanierModel::listerClient();

        // vérifier que le tableau n'est pas vide
        $this->assertNotEmpty($client);

        // vérifier la présence des clés spécifiées sur le 1er client du tableau
        $this->assertArrayHasKey('Id_client', $client[0]);
        $this->assertArrayHasKey('Nom_client', $client[0]);
        $this->assertArrayHasKey('Prenom_client', $client[0]);
        $this->assertArrayHasKey('Adresse_client', $client[0]);
        $this->assertArrayHasKey('Telephone_client', $client[0]);
        $this->assertArrayHasKey('Mail_client', $client[0]);
    }

    public function testAddDBNewCommande() {
        $db = Database::getInstance()->getConnection();

        // créer un client
        $stmt = $db->prepare("
        INSERT INTO client (Nom_client, Prenom_client, Adresse_client, Telephone_client, Mail_client) 
        VALUES ('Test', 'Client', '123 rue Test', '0600000000', 'test@test.com')
    ");
        $stmt->execute();
        $idClient = $db->lastInsertId();

        // créer un vendeur
        $stmt = $db->prepare("
        INSERT INTO vendeur (Nom_vendeur, Prenom_vendeur, Mail_vendeur, Mdp_vendeur, Role) 
        VALUES ('Vendeur', 'Test', 'vendeur@test.com', ?, 'vendeur')
    ");
        $stmt->execute([password_hash('test', PASSWORD_BCRYPT)]);
        $idVendeur = $db->lastInsertId();

        // créer 3 produits
        $stmt = $db->prepare("
        INSERT INTO produit (Nom_produit, Description, Prix_HT, Prix_TTC, Stock, Type_conditionnement, Id_categorie) 
        VALUES (?, 'Ceci est la description', ?, ?, 100, 'vrac', 1)
    ");
        $stmt->execute(['Produit 1', 10.00, 12.00]);
        $idProduit1 = $db->lastInsertId();

        $stmt->execute(['Produit 2', 5.00, 6.00]);
        $idProduit2 = $db->lastInsertId();

        $stmt->execute(['Produit 3', 20.00, 24.00]);
        $idProduit3 = $db->lastInsertId();

        // créer les données de session

        $_SESSION['panier'] = [
            $idProduit1 => ['id' => $idProduit1, 'nom' => 'Produit 1', 'prixht' => 10.00, 'prixttc' => 12.00, 'quantite' => 2],
            $idProduit2 => ['id' => $idProduit2, 'nom' => 'Produit 2', 'prixht' => 5.00, 'prixttc' => 6.00, 'quantite' => 1],
            $idProduit3 => ['id' => $idProduit3, 'nom' => 'Produit 3', 'prixht' => 20.00, 'prixttc' => 24.00, 'quantite' => 1]
        ];
        $_SESSION['clientSession'] = [
            'id' => $idClient,
            'nom' => 'Test',
            'prenom' => 'Client',
            'adresse' => '123 rue Test, 41000 Blois'
        ];
        $_SESSION['montants'] = ['totalHT' => 55.00, 'totalTVA' => 11.00, 'totalTTC' => 66.00];
        $_SESSION['utilisateur'] = ['Id_vendeur' => $idVendeur];

        // créer la commande
        $result = PanierModel::addDB();

        // Déboguer
        var_dump('Result:', $result);

        $this->assertNull($result, "Erreur: " . $result);

        $idCommande = (int)$db->lastInsertId();
        var_dump('ID Commande:', $idCommande);

        $this->assertGreaterThan(0, $idCommande, "Aucune commande créée");

        // vérifier la commande
        $stmt = $db->prepare("SELECT COUNT(*) FROM commande WHERE Id_commande = ?");
        $stmt->execute([$idCommande]);
        $this->assertGreaterThan(0, $stmt->fetchColumn());

        // vérifier les lignes de la commande
        $stmt = $db->prepare("SELECT COUNT(*) FROM ligne_commande WHERE Id_commande = ?");
        $stmt->execute([$idCommande]);
        $this->assertEquals(3, $stmt->fetchColumn());
    }
}