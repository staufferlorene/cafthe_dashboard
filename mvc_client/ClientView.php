<?php

require_once 'init_smarty.php';

class ClientView {
    private $smarty;

    public function __construct() {
        global $smarty;
        $this->smarty = $smarty;
        $this->smarty->setTemplateDir(__DIR__ . '/mvc_client');
    }

    /**
     * Affiche la liste des clients
     * @param array $clients Liste des clients à afficher
     */
    public function afficherListe($clients) {
        $this->smarty->assign('client', $clients);
        $this->smarty->display('mvc_client\client_liste_view.tpl');
    }

    /**
     * Affiche le formulaire d'ajout de client
     * @param string|null $erreur Message d'erreur à afficher
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
     * @param array $client Données du client à modifier
     * @param string|null $erreur Message d'erreur à afficher
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
     * @param object $client Objet client avec les données existantes
     * @param string|null $erreur Message d'erreur à afficher
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
     * Affiche une erreur de client introuvable
     */
    public function afficherErreurClientIntrouvable() {
        $this->smarty->assign('erreur', 'Client introuvable.');
        $this->smarty->display('mvc_client\client_form_view.tpl');
    }

    /**
     * Redirige vers la liste des clients
     */
    public function redirigerVersListe() {
        header("Location: index.php?action=client");
        exit;
    }


    /**
     * Affiche le formulaire du détail d'un client avec ses données
     * @param object $client Objet client avec les données existantes
     * @param string|null $erreur Message d'erreur à afficher
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
     * Affiche le formulaire de modification de client
     * @param array $client Données du client à modifier
     * @param string|null $erreur Message d'erreur à afficher
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