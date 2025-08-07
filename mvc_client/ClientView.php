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
     * Affiche la liste des produits
     * @param array $produits Liste des produits à afficher
     */
    public function afficherListe($clients) {
        $this->smarty->assign('client', $clients);
        $this->smarty->display('mvc_client\client_liste_view.tpl');
    }

    /**
     * Affiche le formulaire d'ajout de produit
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
     * Affiche le formulaire de modification de produit
     * @param array $produit Données du produit à modifier
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
     * Affiche le formulaire de modification avec les données d'un produit existant
     * @param object $produit Objet produit avec les données existantes
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
     * Affiche une erreur de produit introuvable
     */
    public function afficherErreurClientIntrouvable() {
        $this->smarty->assign('erreur', 'Client introuvable.');
        $this->smarty->display('mvc_client\client_form_view.tpl');
    }

    /**
     * Redirige vers la liste des produits
     */
    public function redirigerVersListe() {
        header("Location: index.php?action=client");
        exit;
    }

    /**
     * Affiche une page d'erreur personnalisée
     * @param string $message Message d'erreur à afficher
     * @param string $titre Titre de la page d'erreur
     */
    public function afficherErreur($message, $titre = 'Erreur') {
        $this->smarty->assign('titre', $titre);
        $this->smarty->assign('message', $message);
        $this->smarty->display('mvc_client\erreur_view.tpl');
    }

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
     * @param array $produit Données du produit à modifier
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