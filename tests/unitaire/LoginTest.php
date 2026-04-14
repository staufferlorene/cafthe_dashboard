<?php

namespace unitaire;

use Config\Database;
use mvc_login\LoginModel;
use mvc_vendeur\VendeurModel;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../vendor/autoload.php';

class LoginTest extends TestCase {

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

    // Tester qu'un mail existant retourne un vendeur
    public function testGetVendeurParMailExiste() {

        // Créer un vendeur au préalable
        VendeurModel::ajouter(
            'Doe',
            'John',
            'admin',
            'test@email.com',
            'Motdepassevendeur123.',
        );

        $vendeur = LoginModel::getVendeurParMail('test@email.com');

        $this->assertIsArray($vendeur);
        $this->assertArrayHasKey('Id_vendeur', $vendeur);
        $this->assertArrayHasKey('Mail_vendeur', $vendeur);
    }

    // Tester qu'un mail inexistant retourne false
    public function testGetVendeurParMailInexistant() {
        $vendeur = LoginModel::getVendeurParMail('nexistepas@email.com');

        $this->assertFalse($vendeur);
    }

    // Tester connexion avec mot de passe correct
    public function testVerifierMotDePasseCorrect() {
        $hash = password_hash('Testdumdp123.', PASSWORD_BCRYPT);

        $result = LoginModel::verifierMotDePasse('Testdumdp123.', $hash);

        $this->assertTrue($result);
    }

    // Tester connexion avec mot de passe incorrect
    public function testVerifierMotDePasseIncorrect() {
        $hash = password_hash('Cestmonmdp123.', PASSWORD_BCRYPT);

        $result = LoginModel::verifierMotDePasse('Mauvaismdp123.', $hash);

        $this->assertFalse($result);
    }
}