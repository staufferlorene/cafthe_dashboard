<?php

use mvc_client\ClientModel;

require_once 'mvc_client/ClientModel.php';
require_once 'mvc_client/ClientView.php';

class ClientController {
    private $clientModel;
    private $clientView;

    public function __construct() {
        $this->clientModel = new ClientModel();
        $this->clientView = new ClientView();
    }

    // Afficher les clients
    public function liste() {
        $clients = $this->clientModel->lister();
        $this->clientView->afficherListe($clients);
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

    // Modifier un client
    public function modifier($Id_client) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifie que les champs sont bien envoyés
            if (isset($_POST['nom'], $_POST['prenom'], $_POST['adresse'], $_POST['tel'], $_POST['mail'])) {
                // Tente la modification
                $erreur = $this->clientModel->modifier($_POST['nom'], $_POST['prenom'], $_POST['adresse'], $_POST['tel'], $_POST['mail'], $Id_client);
                if ($erreur === null) {
                    // Si succès : Redirection vers la liste après modification
                    $this->clientView->redirigerVersListe();
                } else {
                    // Si échec : Affiche le formulaire avec message d'erreur
                    $client = ClientModel::loadById($Id_client);
                    if ($client) {
                        $this->clientView->afficherFormulaireModificationAvecDonnees($client, $erreur);
                    } else {
                        $this->clientView->afficherErreurClientIntrouvable();
                    }
                }
            }
        } else {
            // Affichage du formulaire avec les données existantes
            $client = ClientModel::loadById($Id_client);
            if ($client) {
                $this->clientView->afficherFormulaireModificationAvecDonnees($client);
            } else {
                $this->clientView->afficherErreurClientIntrouvable();
            }
        }
    }

    // Voir le détail d'un client
    public function voirDetail($Id_client) {
        // Affichage du formulaire avec les données existantes
        $client = ClientModel::loadById($Id_client);
        if ($client) {
            $this->clientView->afficherFormulaireDetailAvecDonnees($client);
        } else {
            $this->clientView->afficherErreurClientIntrouvable();
        }
    }
}