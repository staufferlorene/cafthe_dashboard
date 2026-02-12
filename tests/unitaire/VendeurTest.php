<?php

namespace unitaire;

use Database;
use mvc_vendeur\VendeurModel;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../mvc_vendeur/VendeurModel.php';
require_once 'config/Database.php';

class VendeurTest extends TestCase {

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

    // Tester l'ajout de vendeur
    public function testCreateVendeur() {

        // créer un nouveau vendeur
        $vendeur = VendeurModel::ajouter(
            'Durand',
            'Jeanne',
            'vendeur',
            'mail.vendeur@email.com',
            'Motdepassevendeur123',
        );

        // vérification si null est retourné, si oui insertion en BDD est OK
        $this->assertNull($vendeur);
    }

    // Tester si la liste des vendeurs retourne un tableau
    public function testReadVendeurReturnArray() {
        $vendeur = VendeurModel::lister();
        $this->assertIsArray($vendeur);
    }

    // Tester si la liste contient les champs attendus
    public function testReadVendeurContent() {

        // créer un nouveau vendeur au préalable
        VendeurModel::ajouter(
            'Durand',
            'Jeanne',
            'vendeur',
            'mail.vendeur@email.com',
            'Motdepassevendeur123',
        );

        // lister les vendeurs
        $vendeur = VendeurModel::lister();

        // vérifier que le tableau n'est pas vide
        $this->assertNotEmpty($vendeur);

        // vérifier la présence des clés spécifiées sur le 1er produit du tableau
        $this->assertArrayHasKey('Id_vendeur', $vendeur[0]);
        $this->assertArrayHasKey('Nom_vendeur', $vendeur[0]);
        $this->assertArrayHasKey('Prenom_vendeur', $vendeur[0]);
        $this->assertArrayHasKey('Role', $vendeur[0]);
        $this->assertArrayHasKey('Mail_vendeur', $vendeur[0]);
    }

    // Tester le chargement d'un vendeur par son id
    public function testLoadVendeurById() {

        // créer un nouveau vendeur au préalable
        VendeurModel::ajouter(
            'Durand',
            'Jeanne',
            'vendeur',
            'mail.vendeur@email.com',
            'Motdepassevendeur123',
        );

        $vendeur = VendeurModel::loadById(1);
        $this->assertInstanceOf(VendeurModel::class, $vendeur);
    }

    // Tester chargement d'un vendeur inexistant (retourne null)
    public function testLoadVendeurNull() {
        $vendeur = VendeurModel::loadById(999999999999);
        $this->assertNull($vendeur);
    }

    // Tester la modification
    public function testUpdateVendeur() {

        // créer un nouveau vendeur au préalable
        VendeurModel::ajouter(
            'Durand',
            'Jeanne',
            'vendeur',
            'mail.vendeur@email.com',
            'Motdepassevendeur123',
        );

        // récupération de l'id vendeur généré
        $db = Database::getInstance()->getConnection();
        $idVendeur = $db->lastInsertId();

        // modification du vendeur
        VendeurModel::modifier(
            'Nouveaunom',
            'Nouveauprenom',
            'admin',
            'nouveau.mail@email.com',
            'Nouveaumotdepassevendeur123',
            $idVendeur
        );

        // vérifier si les modifications ont été faites
        $vendeurUpdate = VendeurModel::loadById($idVendeur);

        $this->assertEquals('Nouveaunom', $vendeurUpdate->getNom_vendeur());
        $this->assertEquals('Nouveauprenom', $vendeurUpdate->getPrenom_vendeur());
        $this->assertEquals('admin', $vendeurUpdate->getRole());
        $this->assertEquals('nouveau.mail@email.com', $vendeurUpdate->getMail_vendeur());
        $this->assertEquals('Nouveaumotdepassevendeur123', $vendeurUpdate->getMdp_vendeur());
    }

    // Tester la suppression d'un vendeur
    public function testDeleteVendeur() {

        // créer un nouveau vendeur au préalable
        VendeurModel::ajouter(
            'Durand',
            'Jeanne',
            'vendeur',
            'mail.vendeur@email.com',
            'Motdepassevendeur123',
        );

        // récupération de l'id vendeur généré
        $db = Database::getInstance()->getConnection();
        $idVendeur = $db->lastInsertId();

        // supprimer le vendeur
        $vendeurDelete = VendeurModel::delete($idVendeur);

        // vérifier si vendeur bien supprimé et plus présent en BDD
        $this->assertTrue($vendeurDelete);
        $this->assertNull(VendeurModel::loadById($idVendeur));
    }

// Tester le retour booleen si vendeur est lié à une commande
    public function testHaveOrderVendeur() {

        // créer un nouveau vendeur au préalable
        VendeurModel::ajouter(
            'Durand',
            'Jeanne',
            'vendeur',
            'mail.vendeur@email.com',
            'Motdepassevendeur123',
        );

        // récupération de l'id vendeur généré
        $db = Database::getInstance()->getConnection();
        $idVendeur = $db->lastInsertId();

        // vérifier si booleen
        $result = VendeurModel::haveOrder($idVendeur);
        $this->assertIsBool($result);
    }
}