<?php

namespace unitaire;

use Database;
use mvc_home\HomeModel;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../mvc_home/HomeModel.php';
require_once 'config/Database.php';

class HomeTest extends TestCase {

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

    // Tester si listerProduitsVendus() retourne un tableau
    public function testListerProduitsVendusReturnArray() {
        $result = HomeModel::listerProduitsVendus();
        $this->assertIsArray($result);
    }

    // Tester si listerVentesParCategories() retourne un tableau
    public function testListerVentesParCategoriesReturnArray() {
        $result = HomeModel::listerVentesParCategories();
        $this->assertIsArray($result);
    }

    // Tester si listerVentesParMois() retourne un tableau
    public function testListerVentesParMoisReturnArray() {
        $result = HomeModel::listerVentesParMois();
        $this->assertIsArray($result);
    }

    // Tester si listerCAParVendeur() retourne un tableau
    public function testListerCAParVendeurReturnArray() {
        $result = HomeModel::listerCAParVendeur();
        $this->assertIsArray($result);
    }

    // Tester si les champs attendus sont présent (si données existent) pour liste des produits vendus
    public function testListerProduitsVendusContent() {
        $result = HomeModel::listerProduitsVendus();

        // Si des données existent
        if (!empty($result)) {
            // Vérifier que le premier élément contient les clés spécifiées
            $this->assertArrayHasKey('Nom_produit', $result[0]);
            $this->assertArrayHasKey('qte_totale_vendu', $result[0]);
        } else {
            // Si pas de données, vérifier que c'est un tableau vide
            $this->assertEmpty($result);
        }
    }

    // Tester si les champs attendus sont présent (si données existent) pour ventes par catégorie
    public function testListerVentesParCategoriesContent() {
        $result = HomeModel::listerVentesParCategories();

        // Si des données existent
        if (!empty($result)) {
            // Vérifier que le premier élément contient les clés spécifiées
            $this->assertArrayHasKey('Nom_categorie', $result[0]);
            $this->assertArrayHasKey('montant_total', $result[0]);
        } else {
            // Si pas de données, vérifier que c'est un tableau vide
            $this->assertEmpty($result);
        }
    }

    // Tester si les champs attendus sont présent (si données existent) pour ventes par mois
    public function testListerVentesParMoisContent() {
        $result = HomeModel::listerVentesParMois();

        // Si des données existent
        if (!empty($result)) {
            // Vérifier que le premier élément contient les clés spécifiées
            $this->assertArrayHasKey('mois', $result[0]);
            $this->assertArrayHasKey('total_mois', $result[0]);
        } else {
            // Si pas de données, vérifier que c'est un tableau vide
            $this->assertEmpty($result);
        }
    }

    // Tester si les champs attendus sont présent (si données existent) pour CA par vendeur
    public function testListerCAParVendeurContent() {
        $result = HomeModel::listerCAParVendeur();

        // Si des données existent
        if (!empty($result)) {
            // Vérifier que le premier élément contient les clés spécifiées
            $this->assertArrayHasKey('Nom_vendeur', $result[0]);
            $this->assertArrayHasKey('Prenom_vendeur', $result[0]);
            $this->assertArrayHasKey('ca_total', $result[0]);
        } else {
            // Si pas de données, vérifier que c'est un tableau vide
            $this->assertEmpty($result);
        }
    }

    // Tester que listerProduitsVendus() retourne maximum 6 produits
    public function testListerProduitsVendusLimitSix() {
        $result = HomeModel::listerProduitsVendus();

        // Vérifier que le tableau contient au maximum 6 éléments
        $this->assertLessThanOrEqual(6, count($result));
    }

    // Tester que les nouvelles commandes sont inclus dans les stats
    public function testStatWithNewCommande() {

        // Récupérer le nombre de commandes avant
        $ventesBefore = HomeModel::listerVentesParMois();
        $totalBefore = array_sum(array_column($ventesBefore, 'total_mois'));

        // Créer une nouvelle commande
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("
            INSERT INTO commande 
            (Date_commande, Statut_commande, Adresse_livraison, 
             Montant_commande_HT, Montant_TVA, Montant_commande_TTC, 
             Id_vendeur, Id_client) 
            VALUES (NOW(), 'Livrée', 'Adresse test', 50.00, 10.00, 60.00, 1, 1)
        ");
        $stmt->execute();

        // Récupérer les statistiques après
        $ventesAfter = HomeModel::listerVentesParMois();
        $totalAfter = array_sum(array_column($ventesAfter, 'total_mois'));

        // Vérifier que le total a augmenté
        $this->assertGreaterThan($totalBefore, $totalAfter);
    }
}