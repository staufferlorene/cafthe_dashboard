<?php

namespace unitaire;

use Database;
use mvc_client\ClientModel;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../mvc_client/ClientModel.php';
require_once 'config/Database.php';

class ClientTest extends TestCase {

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

    // Tester l'ajout d'un client
    public function testCreateClient() {

        // créer un nouveau client
        $result = ClientModel::ajouter(
            'Dupont',
            'Jean',
            '10 rue de Paris, 75000 Paris',
            '0612345678',
            'jean.dupont@email.com',
        );

        // vérification si null est retourné, si oui insertion en BDD est OK
        $this->assertNull($result);
    }

    // Tester si la liste des clients retourne un tableau
    public function testReadClientReturnArray() {
        $clients = ClientModel::lister();
        $this->assertIsArray($clients);
    }

    // Tester si la liste contient les champs attendus
    public function testReadClientContent() {
        // créer un nouveau client au préalable
        ClientModel::ajouter(
            'Dupont',
            'Jean',
            '10 rue de Paris, 75000 Paris',
            '0612345678',
            'jean.dupont@email.com',
        );

        // lister les clients
        $clients = ClientModel::lister();

        // vérifier que le tableau n'est pas vide
        $this->assertNotEmpty($clients);

        // vérifier la présence des clés spécifiques sur le 1er client du tableau
        $this->assertArrayHasKey('Id_client', $clients[0]);
        $this->assertArrayHasKey('Nom_client', $clients[0]);
        $this->assertArrayHasKey('Prenom_client', $clients[0]);
        $this->assertArrayHasKey('Telephone_client', $clients[0]);
        $this->assertArrayHasKey('Mail_client', $clients[0]);
        $this->assertArrayHasKey('Adresse_client', $clients[0]);
    }

    // Tester le chargement d'un client par son id
    public function testLoadClientById() {

        // créer un nouveau client au préalable
        ClientModel::ajouter(
            'Dupont',
            'Jean',
            '10 rue de Paris, 75000 Paris',
            '0612345678',
            'jean.dupont@email.com',
        );

        $client = ClientModel::loadById(1);
        $this->assertInstanceOf(ClientModel::class, $client);
    }

    // Tester chargement d'un client inexistant (retourne null)
    public function testLoadClientNull() {
        $client = ClientModel::loadById(999999999999);
        $this->assertNull($client);
    }

    // Tester la modification
    public function testUpdateClient() {

        // créer un nouveau client au préalable
        ClientModel::ajouter(
            'Dupont',
            'Jean',
            '10 rue de Paris, 75000 Paris',
            '0612345678',
            'jean.dupont@email.com',
        );

        // récupération de l'id client généré
        $db = Database::getInstance()->getConnection();
        $idClient = $db->lastInsertId();

        // modification du client
        ClientModel::modifier(
            'Modification nom',
            'Modification prénom',
            '10 rue de la modification, 41000 Blois',
            '0699999999',
            'modif.mail@email.com',
            $idClient
        );

        // vérifier si les modifications ont été faites
        $clientUpdate = ClientModel::loadById($idClient);

        $this->assertEquals('Modification nom', $clientUpdate->getNom_client());
        $this->assertEquals('Modification prénom', $clientUpdate->getPrenom_client());
        $this->assertEquals('0699999999', $clientUpdate->getTelephone_client());
        $this->assertEquals('10 rue de la modification, 41000 Blois', $clientUpdate->getAdresse_client());
        $this->assertEquals('modif.mail@email.com', $clientUpdate->getMail_client());
    }

    // Tester la suppression d'un client
    public function testDeleteClient() {

        // créer un nouveau client au préalable
        ClientModel::ajouter(
            'Dupont',
            'Jean',
            '10 rue de Paris, 75000 Paris',
            '0612345678',
            'jean.dupont@email.com',
        );

        // récupération de l'id client généré
        $db = Database::getInstance()->getConnection();
        $idClient = $db->lastInsertId();

        // supprimer le client
        $clientDelete = ClientModel::delete($idClient);

        // vérifier si client bien supprimé et plus présent en BDD
        $this->assertTrue($clientDelete);
        $this->assertNull(ClientModel::loadById($idClient));
    }

    // Tester le retour booleen si client est lié à une commande
    public function testHaveOrderClient() {

        // créer un nouveau client au préalable
        ClientModel::ajouter(
            'Dupont',
            'Jean',
            '10 rue de Paris, 75000 Paris',
            '0612345678',
            'jean.dupont@email.com',
        );

        // récupération de l'id client généré
        $db = Database::getInstance()->getConnection();
        $idClient = $db->lastInsertId();

        // vérifier si booleen
        $result = ClientModel::haveOrder($idClient);
        $this->assertIsBool($result);
    }
}