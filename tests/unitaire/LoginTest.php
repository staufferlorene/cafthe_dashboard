<?php

namespace unitaire;

use Database;
use mvc_login\LoginModel;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../mvc_login/LoginModel.php';
require_once 'config/Database.php';

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
        $hash = password_hash('testdumdp123', PASSWORD_DEFAULT);

        $result = LoginModel::verifierMotDePasse('testdumdp123', $hash);

        $this->assertTrue($result);
    }

    // Tester connexion avec mot de passe incorrect
    public function testVerifierMotDePasseIncorrect()
    {
        $hash = password_hash('monmdp123', PASSWORD_DEFAULT);

        $result = LoginModel::verifierMotDePasse('mauvaismdp', $hash);

        $this->assertFalse($result);
    }
}