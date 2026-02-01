<?php

use mvc_panier\PanierModel;

require_once 'mvc_panier/PanierModel.php';
require_once 'mvc_panier/PanierView.php';

/**
 * Contrôleur pour la gestion du panier
 *
 * Cette classe gère la logique métier entre le modèle {@see PanierModel}
 * et la vue {@see PanierView}, dans le cadre du pattern MVC.
 *
 * Elle permet de :
 * - afficher la liste des produits avec le panier
 * - ajouter un produit au panier
 * - modifier la quantité d'un produit dans le panier
 * - supprimer un produit du panier
 * - afficher le détail du panier
 * - sélectionner un client
 * - valider et enregistrer la commande
 */

class PanierController {
    private $panierModel;
    private $panierView;

    /**
     * Constructeur : initialise le modèle et la vue associés au panier
     */
    public function __construct() {
        $this->panierModel = new PanierModel();
        $this->panierView = new PanierView();
    }

    /**
     * Affiche la liste des produits disponibles avec le panier actuel
     *
     * @return void
     */
    public function liste() {
        $panier = $_SESSION['panier'] ?? [];
        $totaux = $this->panierModel->calculTotaux($panier);

        $produits = $this->panierModel->lister();
        $this->panierView->afficherListe(
            $produits,
            $panier,
            $totaux['totalTTC']
        );
    }

    /**
     * Ajoute un produit au panier ou met à jour sa quantité
     *
     * Si le produit existe déjà dans le panier, incrémente sa quantité.
     * Sinon, crée une nouvelle entrée dans le panier.
     * Utilise $_SESSION['panier'] pour stocker les données.
     *
     * @return void
     */
    public function ajoutPanier() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifie que les champs sont bien envoyés
            if (isset($_POST['id'], $_POST['nom'], $_POST['prixht'], $_POST['prixttc'], $_POST['quantite'])) {
                // Assignation des variables
                $id = $_POST['id'];
                $nom = $_POST['nom'];
                $prixht = $_POST['prixht'];
                $prixttc = $_POST['prixttc'];
                $quantite = $_POST['quantite'];
            }

            // Si le panier n'existe pas on le crée et initialise avec un tableau vide
            if (!isset($_SESSION['panier'])) {
                $_SESSION['panier'] = [];
            }

            // Si le produit n'existe pas dans le panier on le crée
            if (!isset($_SESSION['panier'][$id])) {
                $_SESSION['panier'][$id] = [
                    'id' => $id,
                    'nom' => $nom,
                    'prixht' => (float)$prixht,
                    'prixttc' => (float)$prixttc,
                    'quantite' => (int)$quantite
                ];
            } else {
                // Si le produit existe déjà on màj la quantité
                $_SESSION['panier'][$id]['quantite'] += (int)$quantite;
            }
        }
    }

    /**
     * Affiche le détail complet du panier avec les totaux
     *
     * @return void
     */
    public function voirPanier() {
        $panier = $_SESSION['panier'] ?? [];
        $totaux = $this->panierModel->calculTotaux($panier);
        $this->panierView->afficherDetailPanier(
            $panier,
            $totaux['totalHT'],
            $totaux['totalTVA'],
            $totaux['totalTTC']
        );
    }

    /**
     * Supprime un produit du panier à partir de son identifiant (passé en GET)
     *
     * Récupère l'ID via $_GET['id'] et supprime le produit du panier,
     * puis affiche le panier actualisé
     *
     * @return void
     */
    public function delete() {
        $this->panierModel->delete($_GET['id']);
        // Rappeler la fonction pour afficher le panier
        $this->voirPanier();
    }

    /**
     * Modifie la quantité d'un produit dans le panier
     *
     * Met à jour la quantité du produit spécifié et affiche le panier actualisé
     *
     * @return void
     */
    public function modifierPanier() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifie que les champs sont bien envoyés
            if (isset($_POST['id'], $_POST['quantite'])) {
                // Assignation des variables
                $id = $_POST['id'];
                $quantite = $_POST['quantite'];

                // Mise à jour du panier
                $_SESSION['panier'][$id]['quantite'] = $quantite;
            }
        }

        $panier = $_SESSION['panier'];
        $totaux = $this->panierModel->calculTotaux($panier);
        $this->panierView->afficherDetailPanier(
            $panier,
            $totaux['totalHT'],
            $totaux['totalTVA'],
            $totaux['totalTTC']
        );
    }

    /**
     * Affiche la liste des clients pour sélection
     *
     * @return void
     */
    public function listeChoixClient() {
        $clients = $this->panierModel->listerClient();
        $this->panierView->afficherClient($clients);
    }

    /**
     * Valide le choix du client et affiche le récapitulatif avant paiement
     *
     * Enregistre en session :
     * - Les informations du client sélectionné (id, nom, prénom, adresse)
     * - Les montants totaux (HT, TVA, TTC)
     * Puis affiche le récapitulatif complet de la commande
     *
     * @return void
     */
    public function checkPanier() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifie que les champs sont bien envoyés
            if (isset($_POST['id'], $_POST['nom'], $_POST['prenom'], $_POST['adresse'])) {
                // Assignation des variables
                $id = $_POST['id'];
                $nom = $_POST['nom'];
                $prenom = $_POST['prenom'];
                $adresse = $_POST['adresse'];
            }

            // Création variable pour stocker le client à chaque fois comme ça toujours à jour
            $_SESSION['clientSession'] = [
                'id' => $id,
                'nom' => $nom,
                'prenom' => $prenom,
                'adresse' => $adresse
            ];

            $panier = $_SESSION['panier'];
            $totaux = $this->panierModel->calculTotaux($panier);

            // Création variable pour stocker les montants totaux à chaque fois comme ça toujours à jour
            $_SESSION['montants'] = [
                'totalHT' => $totaux['totalHT'],
                'totalTVA' => $totaux['totalTVA'],
                'totalTTC' => $totaux['totalTTC'],
            ];

            $this->panierView->afficherRecapitulatifPanier();
        }
    }

    /**
     * Enregistre la commande en base de données et finalise le paiement
     *
     * Si l'enregistrement réussit :
     * - Vide le panier et les données de session
     * - Affiche la page de confirmation
     * Si l'enregistrement échoue :
     * - Affiche un message d'erreur
     *
     * @return void
     */
    public function paiementPanier() {

    // Tente l'ajout de la commande en DB
    $erreur = $this->panierModel->addDB();
        if ($erreur === null) {
            // Si succès :
            // Vide les variables de session : panier, client et montants
            $_SESSION['panier'] = [];
            $_SESSION['clientSession'] = [];
            $_SESSION['montants'] = [];

            // Redirection vers la liste après ajout
            $this->panierView->panierPaye();
        } else {
            // Si échec : Affiche la page avec message d'erreur
            $erreur = "Une erreur est survenue lors de l'enregistrement de la commande.";
            $this->panierView->panierPaye($erreur);
        }
    }
}