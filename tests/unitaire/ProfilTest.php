<?php

namespace unitaire;

use Database;
use mvc_profil\ProfilModel;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../mvc_profil/ProfilModel.php';
require_once 'config/Database.php';

class ProfilTest extends TestCase {

    // démarre une transaction, mettant en "attente" les modifications sur la BDD, puis les tests se lancent
    protected function setUp(): void {
        $db = Database::getInstance()->getConnection();
        $db->beginTransaction();

        // initialiser la session si elle n'existe pas
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // nettoyer la variable de session avant chaque test
        $_SESSION = [];
    }

    // annule toutes les modifications à la fin de CHAQUE test, la BDD reste propre
    protected function tearDown(): void {
        $db = Database::getInstance()->getConnection();
        $db->rollBack();

        // vider la session
        $_SESSION = [];
    }

    // Tester le chargement du profil d'un vendeur par son id
    public function testLoadProfilById() {

        // Créer un vendeur au préalable
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            INSERT INTO vendeur (Nom_vendeur, Prenom_vendeur, Mail_vendeur, Mdp_vendeur, Role) 
            VALUES ('Dodoe', 'Jean', 'jean.dodoe@email.com', ?, 'vendeur')
        ");
        $stmt->execute([password_hash('mdp123', PASSWORD_BCRYPT)]);
        $idVendeur = (int)$db->lastInsertId();

        // charger le profil et vérifier les informations
        $profil = ProfilModel::loadById($idVendeur);

        $this->assertInstanceOf(ProfilModel::class, $profil);
        $this->assertEquals('Dodoe', $profil->getNom_vendeur());
        $this->assertEquals('Jean', $profil->getPrenom_vendeur());
        $this->assertEquals('jean.dodoe@email.com', $profil->getMail_vendeur());
    }

    // Tester chargement d'un profil vendeur inexistant (retourne null)
    public function testLoadProfilNull() {
        $profil = ProfilModel::loadById(999999999999);
        $this->assertNull($profil);
    }

    // Tester la modification des infos du profil (sans mot de passe)
    public function testUpdateProfil() {

        // Créer un vendeur au préalable
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            INSERT INTO vendeur (Nom_vendeur, Prenom_vendeur, Mail_vendeur, Mdp_vendeur, Role) 
            VALUES ('Dodoe', 'Jean', 'jean.dodoe@email.com', ?, 'vendeur')
        ");
        $mdpOriginal = password_hash('mdp123', PASSWORD_BCRYPT);
        $stmt->execute([$mdpOriginal]);
        $idVendeur = (int)$db->lastInsertId();

        // Modifier le profil SANS changer le mot de passe
        $result = ProfilModel::modifier(
            'Dupont',
            'John',
            'john.dupont@email.com',
            $idVendeur
        );

        // vérifier que la modification a réussi
        $this->assertNull($result);

        // vérifier que les modifications soient correctes
        $profil = ProfilModel::loadById($idVendeur);

        $this->assertEquals('Dupont', $profil->getNom_vendeur());
        $this->assertEquals('John', $profil->getPrenom_vendeur());
        $this->assertEquals('john.dupont@email.com', $profil->getMail_vendeur());

        // vérifier que le mot de passe N'A PAS changé
        $stmt = $db->prepare("SELECT Mdp_vendeur FROM vendeur WHERE Id_vendeur = ?");
        $stmt->execute([$idVendeur]);
        $mdpActuel = $stmt->fetchColumn();

        $this->assertEquals($mdpOriginal, $mdpActuel);
    }

    // ==========================================
    // TESTS DE MODIFICATION DU MOT DE PASSE
    // ==========================================

    // Test : Modifier le mot de passe avec l'ancien mot de passe correct
    public function testUpdatePasswordOK() {

        // Créer un vendeur au préalable
        $db = Database::getInstance()->getConnection();

        $ancienMdp = 'AncienMotDePasse123';
        $stmt = $db->prepare("
            INSERT INTO vendeur (Nom_vendeur, Prenom_vendeur, Mail_vendeur, Mdp_vendeur, Role) 
            VALUES ('Dupont', 'Jean', 'jean.dupont@email.com', ?, 'vendeur')
        ");
        $stmt->execute([password_hash($ancienMdp, PASSWORD_BCRYPT)]);
        $idVendeur = (int)$db->lastInsertId();

        // Modifier le mot de passe
        $nouveauMdp = 'NouveauMotDePasse456';
        $result = ProfilModel::modifierMdp(
            $ancienMdp,
            $nouveauMdp,
            $idVendeur
        );

        // Vérifier qu'il n'y a pas d'erreur
        $this->assertNull($result);

        // Récupérer le nouveau hash
        $stmt = $db->prepare("SELECT Mdp_vendeur FROM vendeur WHERE Id_vendeur = ?");
        $stmt->execute([$idVendeur]);
        $nouveauHash = $stmt->fetchColumn();

        // Vérifier que le nouveau mot de passe fonctionne
        $this->assertTrue(password_verify($nouveauMdp, $nouveauHash));

        // Vérifier que l'ancien mot de passe ne fonctionne plus
        $this->assertFalse(password_verify($ancienMdp, $nouveauHash));
    }

    // Tester la modification du mdp avec un mauvais ancien mot de passe
    public function testUpdatePasswordWithPasswordOldKO() {

        // Créer un vendeur au préalable
        $db = Database::getInstance()->getConnection();
        
        $oldPassword = 'MotDePasse123';
        $stmt = $db->prepare("
            INSERT INTO vendeur (Nom_vendeur, Prenom_vendeur, Mail_vendeur, Mdp_vendeur, Role) 
            VALUES ('Dupont', 'Jean', 'jean.dupont@email.com', ?, 'vendeur')
        ");
        $hashOriginal = password_hash($oldPassword, PASSWORD_BCRYPT);
        $stmt->execute([$hashOriginal]);
        $idVendeur = (int)$db->lastInsertId();

        // Tenter de modifier avec un MAUVAIS ancien mot de passe
        $result = ProfilModel::modifierMdp(
            'MauvaisMotDePasse',
            'NouveauMotDePasse456',
            $idVendeur
        );

        // Vérifier qu'une erreur est retournée
        $this->assertNotNull($result);

        // Vérifier que le mot de passe N'A PAS changé
        $stmt = $db->prepare("SELECT Mdp_vendeur FROM vendeur WHERE Id_vendeur = ?");
        $stmt->execute([$idVendeur]);
        $hashActuel = $stmt->fetchColumn();

        $this->assertEquals($hashOriginal, $hashActuel);

        // Vérifier que l'ancien mot de passe fonctionne toujours
        $this->assertTrue(password_verify($oldPassword, $hashActuel));
    }
}