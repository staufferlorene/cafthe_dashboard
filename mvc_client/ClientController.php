<?php

use mvc_client\ClientModel;

require_once 'mvc_client/ClientModel.php';
require_once 'mvc_client/ClientView.php';

/**
 * Contrôleur pour la gestion des clients
 *
 * Cette classe gère la logique métier entre le modèle {@see ClientModel}
 * et la vue {@see ClientView}, dans le cadre du pattern MVC.
 *
 * Elle permet de :
 * - lister les clients
 * - ajouter un client
 * - modifier un client
 * - supprimer un client
 * - afficher le détail d’un client
 */

class ClientController {
    private $clientModel;
    private $clientView;


    /**
     * Constructeur : initialise le modèle et la vue associés aux clients
     */
    public function __construct() {
        $this->clientModel = new ClientModel();
        $this->clientView = new ClientView();
    }

    /**
     * Affiche la liste de tous les clients
     *
     * @return void
     */
    public function liste() {
        $clients = $this->clientModel->lister();

        // Vérifie si au moins une commande est rattachée au client
        foreach ($clients as &$client) {
            $client['haveOrder'] = ClientModel::haveOrder($client['Id_client']);
        }

        $this->clientView->afficherListe($clients);
    }

    /**
     * Ajoute un nouveau client
     *
     * Si la requête est en POST et valide, tente l’ajout en base de données :
     *   - redirige vers la liste si succès,
     *   - affiche le formulaire avec un message d’erreur si échec.
     * Sinon affiche le formulaire d’ajout vide
     *
     * @return void
     */
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

    /**
     * Supprime un client à partir de son identifiant (passé en GET)
     *
     * @return void
     */
    public function delete() {
        $this->clientModel->delete($_GET['Id_client']);
        // Rappeler la fonction pour afficher
        $this->liste();
    }

    /**
     * Modifie un client existant
     *
     * Si la requête est en POST et valide, tente la modification :
     *   - redirige vers la liste si succès,
     *   - affiche le formulaire de modification avec erreurs si échec.
     * Si la requête est en GET :
     *  - affiche le formulaire prérempli si le client existe,
     *  - sinon affiche une erreur.
     *
     * @param int $Id_client Identifiant du client à modifier
     *
     * @return void
     */
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

    /**
     *  Affiche le détail d’un client existant
     *
     *  - Si le client est trouvé, affiche le formulaire de détail,
     *  - Sinon affiche une erreur "introuvable"
     *
     * @param int $Id_client Identifiant client
     * @return void
     */
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