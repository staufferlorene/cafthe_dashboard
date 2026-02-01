<?php

require_once 'init_smarty.php';

/**
 * Vue pour l'affichage des clients
 *
 * Cette classe gère l'affichage des différentes vues liées aux clients
 * via le moteur de template Smarty.
 */

class ClientView {
    private $smarty;

    /**
     * Constructeur : initialise Smarty et définit le répertoire des templates
     */
    public function __construct() {
        global $smarty;
        $this->smarty = $smarty;
        $this->smarty->setTemplateDir(__DIR__ . '/mvc_client');
    }

    /**
     * Affiche la liste des clients
     *
     * @param array $clients Liste des clients à afficher
     *
     * @return void
     */
    public function afficherListe($clients) {
        $this->smarty->assign('client', $clients);
        $this->smarty->display('mvc_client\client_liste_view.tpl');
    }

    /**
     * Affiche le formulaire d'ajout de client
     *
     * @param string|null $erreur Message d'erreur à afficher
     *
     * @return void
     */
    public function afficherFormulaireAjout($erreur = null) {
        $this->smarty->assign('action', 'add');
        if ($erreur) {
            $this->smarty->assign('erreur', $erreur);
        }
        $this->smarty->display('mvc_client\client_form_view.tpl');
    }

    /**
     * Affiche le formulaire de modification de client
     *
     * @param array $client Données du client à modifier
     * @param string|null $erreur Message d'erreur à afficher
     *
     * @return void
     */
    public function afficherFormulaireModification($client, $erreur = null) {
        $this->smarty->assign('action', 'update_client');
        $this->smarty->assign('client', $client);
        if ($erreur) {
            $this->smarty->assign('erreur', $erreur);
        }
        $this->smarty->display('mvc_client/client_form_view.tpl');
    }

    /**
     * Affiche le formulaire de modification avec les données d'un client existant
     *
     * @param object $client Objet client avec les données existantes
     * @param string|null $erreur Message d'erreur à afficher
     *
     * @return void
     */
    public function afficherFormulaireModificationAvecDonnees($client, $erreur = null) {
        $donneesClient = [
            'Id_client' => $client->getId(),
            'Nom_client' => $client->getNom_client(),
            'Prenom_client' => $client->getPrenom_client(),
            'Adresse_client' => $client->getAdresse_client(),
            'Telephone_client' => $client->getTelephone_client(),
            'Mail_client' => $client->getMail_client(),
        ];

        $this->afficherFormulaireModification($donneesClient, $erreur);
    }

    /**
     * Affiche une erreur lorsque le client n'est pas trouvé
     *
     * @return void
     */
    public function afficherErreurClientIntrouvable() {
        $this->smarty->assign('erreur', 'Client introuvable.');
        $this->smarty->display('mvc_client\client_form_view.tpl');
    }

    /**
     * Redirige vers la liste des clients
     *
     * @return void
     */
    public function redirigerVersListe() {
        header("Location: index.php?action=client");
        exit;
    }

    /**
     * Affiche le formulaire du détail d'un client avec ses données
     *
     * @param object $client Objet client avec les données existantes
     * @param string|null $erreur Message d'erreur à afficher
     *
     * @return void
     */
    public function afficherFormulaireDetailAvecDonnees($client, $erreur = null) {
        $donneesClient = [
            'Id_client' => $client->getId(),
            'Nom_client' => $client->getNom_client(),
            'Prenom_client' => $client->getPrenom_client(),
            'Telephone_client' => $client->getTelephone_client(),
            'Adresse_client' =>$client->getAdresse_client(),
            'Mail_client' =>$client->getMail_client(),
        ];

        $this->afficherFormulaireDetail($donneesClient, $erreur);
    }

    /**
     * Affiche le formulaire avec le détail du client
     *
     * @param array $client Données du client
     * @param string|null $erreur Message d'erreur à afficher
     *
     * @return void
     */
    public function afficherFormulaireDetail($client, $erreur = null) {
        $this->smarty->assign('action', 'detail_client');
        $this->smarty->assign('client', $client);
        if ($erreur) {
            $this->smarty->assign('erreur', $erreur);
        }
        $this->smarty->display('mvc_client/client_detail_view.tpl');
    }
}