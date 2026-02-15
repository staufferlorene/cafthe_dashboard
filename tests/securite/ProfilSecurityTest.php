<?php

namespace securite;

use Database;
use mvc_profil\ProfilModel;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

require_once __DIR__ . '/../../mvc_profil/ProfilModel.php';
require_once 'config/Database.php';

class ProfilSecurityTest extends TestCase {

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

    // Test : Tentative d'injection SQL via le nom
    #[DataProvider('payloadsProvider')]
    public function testSQLInjectionInNom(string $payload) {

        $db = Database::getInstance()->getConnection();

        // compter avant injection
        $countsBefore = [
            'vendeur' => (int)$db->query("SELECT COUNT(*) FROM vendeur")->fetchColumn(),
            'produit' => (int)$db->query("SELECT COUNT(*) FROM produit")->fetchColumn(),
            'client' => (int)$db->query("SELECT COUNT(*) FROM client")->fetchColumn(),
            'commande' => (int)$db->query("SELECT COUNT(*) FROM commande")->fetchColumn(),
        ];

        // créer un vendeur au préalable
        $stmt = $db->prepare("
            INSERT INTO vendeur (Nom_vendeur, Prenom_vendeur, Mail_vendeur, Mdp_vendeur, Role) 
            VALUES ('Dupont', 'Jean', 'jean@email.com', ?, 1)
        ");
        $stmt->execute([password_hash('mdp123', PASSWORD_BCRYPT)]);
        $idVendeur = (int)$db->lastInsertId();

        // faire l'injection
        ProfilModel::modifier(
            $payload,
            'Jean',
            'jean@email.com',
            $idVendeur
        );

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

        // Vérifier que le nombre de lignes dans les différentes tables n'a PAS changé (sauf pour vendeur ou c'est +1)
        // compter les lignes APRES l'injection
        $countsAfter = [
            'vendeur' => (int)$db->query("SELECT COUNT(*) FROM vendeur")->fetchColumn(),
            'produit' => (int)$db->query("SELECT COUNT(*) FROM produit")->fetchColumn(),
            'client' => (int)$db->query("SELECT COUNT(*) FROM client")->fetchColumn(),
            'commande' => (int)$db->query("SELECT COUNT(*) FROM commande")->fetchColumn(),
        ];

        $this->assertEquals($countsBefore['vendeur'] + 1, $countsAfter['vendeur']);
        $this->assertEquals($countsBefore['produit'], $countsAfter['produit']);
        $this->assertEquals($countsBefore['client'], $countsAfter['client']);
        $this->assertEquals($countsBefore['commande'], $countsAfter['commande']);
    }

    // Tester XSS (CROSS-SITE SCRIPTING) dans les champs texte
    public function testXSSProfil() {

        $db = Database::getInstance()->getConnection();

        // créer un vendeur au préalable
        $stmt = $db->prepare("
            INSERT INTO vendeur (Nom_vendeur, Prenom_vendeur, Mail_vendeur, Mdp_vendeur, Role) 
            VALUES ('Dupont', 'Jean', 'jean@email.com', ?, 1)
        ");
        $stmt->execute([password_hash('mdp', PASSWORD_BCRYPT)]);
        $idVendeur = (int)$db->lastInsertId();

        ProfilModel::modifier(
            "<script>alert('XSS')</script>",
            'Jean',
            'jean@email.com',
            $idVendeur
        );

        // Récupérer le profil
        $profil = ProfilModel::loadById($idVendeur);

        // Le nom est stocké tel quel en BDD (pas d'échappement nécessaire ici)
        $this->assertEquals("<script>alert('XSS')</script>", $profil->getNom_vendeur());
        // La protection XSS se fait au niveau de la vue avec Smarty {$variable|escape}
        // Le test vérifie que les données ne cassent pas la BDD
    }

    // Test : L'email doit être au bon format
    public function testEmailValidation() {

        $emailsInvalides = [
            'pas-un-email',
            '@exemple.com',
            'test@',
            'test@@exemple.com',
            'test @exemple.com',
            ''
        ];

        foreach ($emailsInvalides as $email) {
            $isValid = filter_var($email, FILTER_VALIDATE_EMAIL);
            $this->assertFalse($isValid, "L'email '{$email}' devrait être invalide");
        }

        $emailsValides = [
            'test@exemple.com',
            'jean.dupont@exemple.fr',
            'vendeur123@test.ch'
        ];

        foreach ($emailsValides as $email) {
            $isValid = filter_var($email, FILTER_VALIDATE_EMAIL);
            $this->assertNotFalse($isValid, "L'email '{$email}' devrait être valide");
        }
    }
}