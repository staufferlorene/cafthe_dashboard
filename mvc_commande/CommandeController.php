<?php

use mvc_commande\CommandeModel;

require_once 'mvc_commande/CommandeModel.php';
require_once 'mvc_commande/CommandeView.php';

class CommandeController {
    private $commandeModel;
    private $commandeView;

    public function __construct() {
        $this->commandeModel = new CommandeModel();
        $this->commandeView = new CommandeView();
    }

    // Afficher les commandes
    public function liste() {
        $commandes = $this->commandeModel->lister();
        $this->commandeView->afficherListe($commandes);
    }

    // Ajouter un client
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifie que les champs sont bien envoyés
            if (isset($_POST['nom'], $_POST['prenom'], $_POST['adresse'], $_POST['tel'], $_POST['mail'])) {
                // Tente l'ajout
                $erreur = $this->clientModel->ajouter($_POST['nom'], $_POST['prenom'], $_POST['adresse'], $_POST['tel'], $_POST['mail']);

                if ($erreur === null) {
                    // Si succès : Redirection vers la liste après ajout
                    $this->clientView->redirigerVersListe();
                } else {
                    // Si échec : Affiche le formulaire avec message d'erreur
                    $this->clientView->afficherFormulaireAjout($erreur);
                }
            }
        } else {
            // Affiche le formulaire vide
            $this->clientView->afficherFormulaireAjout(null);
        }
    }

    // Supprimer un client
    public function delete() {
        $this->clientModel->delete($_GET['Id_client']);
        // Rappeler la fonction pour afficher
        $this->liste();
    }

    // Modifier une commande
    public function modifier($Id_commande) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifie que les champs sont bien envoyés
            if (isset($_POST['statut'])) {
                // Tente la modification
                $erreur = $this->commandeModel->modifier($_POST['statut'], $Id_commande);
                if ($erreur === null) {
                    // Si succès : Redirection vers la liste après modification
                    $this->commandeView->redirigerVersListe();
                } else {
                    // Si échec : Affiche le formulaire avec message d'erreur
                    $commande = CommandeModel::loadById($Id_commande);
                    if ($commande) {
                        $this->commandeView->afficherFormulaireModificationAvecDonnees($commande, $erreur);
                    } else {
                        $this->commandeView->afficherErreurCommandeIntrouvable();
                    }
                }
            }
        } else {
            // Affichage du formulaire avec les données existantes
            $commande = CommandeModel::loadById($Id_commande);
            if ($commande) {
                $this->commandeView->afficherFormulaireModificationAvecDonnees($commande);
            } else {
                $this->commandeView->afficherErreurCommandeIntrouvable();
            }
        }
    }

    // Voir le détail d'une commande
    public function voirDetail($Id_commande) {
        // Affichage du formulaire avec les données existantes
        $commande = CommandeModel::loadById($Id_commande);
        if ($commande) {
            $this->commandeView->afficherFormulaireDetailAvecDonnees($commande);
        } else {
            $this->commandeView->afficherErreurCommandeIntrouvable();
        }
    }
}