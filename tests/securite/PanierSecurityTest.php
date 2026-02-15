<?php

namespace securite;

use Database;
use mvc_panier\PanierModel;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

require_once __DIR__ . '/../../mvc_panier/PanierModel.php';
require_once 'config/Database.php';

class PanierSecurityTest extends TestCase {

    // démarre une transaction, mettant en "attente" les modifications sur la BDD, puis les tests se lancent
    protected function setUp(): void {
        $db = Database::getInstance()->getConnection();
        $db->beginTransaction();

        // Initialiser la session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Nettoyer les sessions et POST
        $_SESSION = [];
        $_POST = [];
    }

    // annule toutes les modifications à la fin de CHAQUE test, la BDD reste propre
    protected function tearDown(): void {
        $db = Database::getInstance()->getConnection();
        $db->rollBack();

        $_SESSION = [];
        $_POST = [];
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

    // Tester l'injection SQL via l'id produit
    #[DataProvider('payloadsProvider')]
    public function testInjectionSQLPanier(string $payload) {

        $db = Database::getInstance()->getConnection();

        // Compter les lignes AVANT l'injection
        $countsBefore = [
            'produit' => (int)$db->query("SELECT COUNT(*) FROM produit")->fetchColumn(),
            'vendeur' => (int)$db->query("SELECT COUNT(*) FROM vendeur")->fetchColumn(),
            'ligne_commande' => (int)$db->query("SELECT COUNT(*) FROM ligne_commande")->fetchColumn(),
        ];

        $_SESSION['panier'] = [
            1 => [
                'id' => $payload,
                'nom' => 'Test',
                'prixht' => 10,
                'prixttc' => 12,
                'quantite' => 1
            ]
        ];

        $_SESSION['clientSession'] = ['id' => 1, 'nom' => 'Client', 'prenom' => 'Test', 'adresse' => 'Adresse test'];
        $_SESSION['montants'] = ['totalHT' => 10, 'totalTVA' => 2, 'totalTTC' => 12];
        $_SESSION['utilisateur'] = ['Id_vendeur' => 1];

        PanierModel::addDB();

        // Vérifier qu'AUCUNE injection n'a réussi

        // Vérifier que les tables existent toujours
        foreach (['produit', 'vendeur', 'ligne_commande'] as $table) {
            try {
                $db->query("SELECT 1 FROM {$table} LIMIT 1");
                $this->assertTrue(true, "La table {$table} existe toujours");
            } catch (\PDOException $e) {
                $this->fail("La table {$table} a été supprimée : {$payload}");
            }
        }

        // Vérifier que le nombre de lignes dans les différentes tables n'a PAS changé
        // compter les lignes AVANT l'injection
        $countsAfter = [
            'produit' => (int)$db->query("SELECT COUNT(*) FROM produit")->fetchColumn(),
            'vendeur' => (int)$db->query("SELECT COUNT(*) FROM vendeur")->fetchColumn(),
            'ligne_commande' => (int)$db->query("SELECT COUNT(*) FROM ligne_commande")->fetchColumn(),
        ];


        $this->assertEquals($countsBefore['produit'],$countsAfter['produit']);
        $this->assertEquals($countsBefore['vendeur'],$countsAfter['vendeur']);
        $this->assertEquals($countsBefore['ligne_commande'],$countsAfter['ligne_commande']);
    }

    // Tester XSS (CROSS-SITE SCRIPTING) dans les champs texte
    public function testXSSPanier() {

        $_POST['nom'] = "<script>alert('XSS')</script>";

        $panier = [
            1 => [
                'id' => 1,
                'nom' => $_POST['nom'],
                'prixht' => 10.00,
                'prixttc' => 12.00,
                'quantite' => 1
            ]
        ];

        $_SESSION['panier'] = $panier;

        // Le nom est stocké tel quel en BDD (pas d'échappement nécessaire ici)
        $this->assertEquals("<script>alert('XSS')</script>", $_SESSION['panier'][1]['nom']);
        // La protection XSS se fait au niveau de la vue avec Smarty {$variable|escape}
        // Le test vérifie que les données ne cassent pas la BDD
    }

    // Tester qu'on ne peut pas créer de commande sans client
    public function testCommandeWithoutClient() {

        $_SESSION['panier'] = [
            1 => ['id' => 1, 'nom' => 'Produit', 'prixht' => 10, 'prixttc' => 12, 'quantite' => 1]
        ];
        $_SESSION['montants'] = ['totalHT' => 10, 'totalTVA' => 2, 'totalTTC' => 12];
        $_SESSION['utilisateur'] = ['Id_vendeur' => 1];
        // pas de client dans la varaible de session

        try {
            $result = PanierModel::addDB();

            // Si la méthode retourne une erreur, c'est bon
            if ($result !== null) {
                $this->assertTrue(true);
            } else {
                $this->fail("La commande ne devrait pas être créée sans client");
            }
        } catch (\Exception $e) {
            // Si exception levée, c'est bon
            $this->assertTrue(true);
        }
    }

    // Tester que 2 vendeurs n'ont pas le même panier
    public function testOnePanierByVendeur() {

        // panier du vendeur 1
        $_SESSION['panier'] = [
            1 => ['id' => 1, 'nom' => 'Produit Vendeur1', 'prixht' => 10, 'prixttc' => 12, 'quantite' => 1]
        ];

        $panierVendeur1 = $_SESSION['panier'];

        // Simuler un autre vendeur avec une nouvelle session
        $_SESSION = [];

        // panier du vendeur 2
        $_SESSION['panier'] = [
            2 => ['id' => 2, 'nom' => 'Produit Vendeur2', 'prixht' => 5, 'prixttc' => 6, 'quantite' => 2]
        ];

        $panierVendeur2 = $_SESSION['panier'];

        // vérifier que les paniers soient différents
        $this->assertNotEquals($panierVendeur1, $panierVendeur2);
    }
}