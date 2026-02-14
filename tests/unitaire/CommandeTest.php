<?php

namespace unitaire;

use Database;
use mvc_commande\CommandeModel;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../mvc_commande/CommandeModel.php';
require_once 'config/Database.php';

class CommandeTest extends TestCase{

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

    // Tester si la liste des commandes retourne un tableau
    public function testReadCommandeReturnArray() {
        $commande = CommandeModel::lister();
        $this->assertIsArray($commande);
    }

    // Tester si la liste contient les champs attendus
    public function testReadCommandeContent() {

        // lister les commandes
        $commande = CommandeModel::lister();

        // vérifier que le tableau n'est pas vide
        $this->assertNotEmpty($commande);

        // vérifier la présence des clés spécifiées sur la 1ère commande du tableau
        $this->assertArrayHasKey('Id_commande', $commande[0]);
        $this->assertArrayHasKey('Date_commande', $commande[0]);
        $this->assertArrayHasKey('Statut_commande', $commande[0]);
        $this->assertArrayHasKey('Adresse_livraison', $commande[0]);
        $this->assertArrayHasKey('Montant_commande_HT', $commande[0]);
        $this->assertArrayHasKey('Montant_TVA', $commande[0]);
        $this->assertArrayHasKey('Montant_commande_TTC', $commande[0]);
        $this->assertArrayHasKey('Id_vendeur', $commande[0]);
        $this->assertArrayHasKey('Id_client', $commande[0]);
    }

    // Tester le chargement d'une commande et des informations du client associé, grâce à l'id client
    public function testLoadCommandeById() {

        $commande = CommandeModel::loadByIdClient(1);
        $this->assertInstanceOf(CommandeModel::class, $commande);
    }

    // Tester chargement d'une commande inexistante (retourne null)
    public function testLoadProductNull() {
        $commande = CommandeModel::loadByIdClient(999999999999);
        $this->assertNull($commande);
    }

    // Tester que seul le statut puisse être modifié, les autres champs restent inchangés
    public function testUpdateCommande() {

        // charger la commande avant modification
        $commandeBefore = CommandeModel::loadByIdClient(1);

        // sauvegarder chaque données
        $statutSave = $commandeBefore->getStatut_commande();
        $nomSave = $commandeBefore->getNom_client();
        $prenomSave = $commandeBefore->getPrenom_client();
        $adresseSave = $commandeBefore->getAdresse_livraison();
        $dateCommandeSave = $commandeBefore->getDate_commande();
        $montantTTCSave = $commandeBefore->getMontant_commande_TTC();

        // modification du statut de la commande
        CommandeModel::modifier(
            'Expédiée',
            1
        );

        // recharger la commande après modification
        $commandeAfter = CommandeModel::loadByIdClient(1);

        // vérifier que seul le statut a été modifié, les autres champs sont inchangés
        $this->assertEquals('Expédiée', $commandeAfter->getStatut_commande());

        $this->assertEquals($nomSave, $commandeAfter->getNom_client());
        $this->assertEquals($prenomSave, $commandeAfter->getPrenom_client());
        $this->assertEquals($adresseSave, $commandeAfter->getAdresse_livraison());
        $this->assertEquals($dateCommandeSave, $commandeAfter->getDate_commande());
        $this->assertEquals($montantTTCSave, $commandeAfter->getMontant_commande_TTC());
    }
}