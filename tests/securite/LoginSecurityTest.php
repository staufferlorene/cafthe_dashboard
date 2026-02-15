<?php

namespace securite;

use mvc_login\LoginModel;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

require_once __DIR__ . '/../../mvc_login/LoginModel.php';
require_once 'config/Database.php';

class LoginSecurityTest extends TestCase {

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

    // Tester l'injection SQL : vérifier qu'aucun vendeur n'est retourné pour des payloads malveillants
    #[DataProvider('payloadsProvider')]
    public function testInjectionSQLLogin(string $payload) {
        $vendeur = LoginModel::getVendeurParMail($payload);
        $this->assertFalse($vendeur);
    }

    // Tester que le mdp soit bien haché
    public function testPasswordIsHash() {
        // hachage du mdp
        $hash = password_hash('test123', PASSWORD_DEFAULT);

        // compare que mdp en clair = mdp haché
        $this->assertTrue(password_verify('test123', $hash));

        // vérifie que les valeurs et les types sont différents
        $this->assertNotSame('test123', $hash);
    }
}